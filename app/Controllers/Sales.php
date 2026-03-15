<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\PaymentMethodModel;
use App\Models\PaymentModel;
use App\Models\PaymentRefundModel;
use App\Models\GroupModel;
use App\Models\CategoryModel;

class Sales extends BaseController
{
    private $model;
    private $detailModel;
    private $customerModel;
    private $productModel;
    private $paymentMethodModel;
    private $paymentModel;
    private $paymentRefundModel;

    private $link = 'sales';
    private $view = 'sales';
    private $title = 'Sales';

    private $model_group;
    private $model_category;

    public function __construct()
    {
        $this->title = temp_lang('sales.sales') ?? 'Sales';
        $this->model = new TransactionModel();
        $this->detailModel = new TransactionDetailModel();
        $this->customerModel = new CustomerModel();
        $this->productModel = new ProductModel();
        $this->paymentMethodModel = new PaymentMethodModel();
        $this->paymentModel = new PaymentModel();
        $this->paymentRefundModel = new PaymentRefundModel();
        $this->model_group = new GroupModel();
        $this->model_category = new CategoryModel();
    }

    public function new()
    {
        $redirect = checkPermission('sales.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        // Fetch Recent Transactions for History
        $history = $this->model->select('transactions.*, users.username as cashier_name')
            ->select('(SELECT SUM(td.qty) FROM transaction_details td WHERE td.transaction_id = transactions.id) as total_items')
            ->select('(SELECT pm.name FROM payments p JOIN payment_methods pm ON pm.id = p.method_id WHERE p.transaction_id = transactions.id LIMIT 1) as payment_method_name')
            ->join('users', 'users.id = transactions.cid', 'left')
            ->orderBy('transactions.id', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'customers' => $this->customerModel->where('status', 'Active')->findAll(),
            'products' => $this->productModel->getProductsWithEffectiveStock(),
            'paymentMethods' => $this->paymentMethodModel->findAll(),
            'groups' => $this->model_group->where('status', 'Active')->findAll(),
            'history' => $history,
        ];

        return view($this->view . '/new', $data);
    }

    public function create()
    {
        $redirect = checkPermission('sales.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $input = $this->request->getVar();
        $isNewCustomer = isset($input['is_new_customer']) && $input['is_new_customer'] == '1';

        $rules = [
            'order_date' => 'required',
            'product_id.*' => 'required',
            'qty.*' => 'required|numeric',
        ];

        if ($isNewCustomer) {
            $rules['customer_name'] = 'required';
            $rules['customer_phone'] = 'required';
            $rules['customer_address'] = 'required';
        } else {
            $rules['customer_id'] = 'required';
        }

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput()->with('error', 'Validation failed. Please check the inputs.');
        }

        $this->db->transBegin();

        try {
            $customerId = $this->request->getVar('customer_id');

            if ($isNewCustomer) {
                $customerGroupId = $this->request->getVar('group_id');
                $newGroup = $this->request->getVar('new_group_name');

                if (!empty($newGroup)) {
                    $groupData = [
                        'name' => $newGroup,
                        'status' => 'Active',
                        'code' => strtoupper(substr($newGroup, 0, 3)) . rand(100, 999)
                    ];
                    $this->model_group->insert($groupData);
                    $customerGroupId = $this->model_group->getInsertID();
                }

                $customerData = [
                    'group_id' => $customerGroupId,
                    'category' => $this->request->getVar('category'),
                    'name' => $this->request->getVar('customer_name', FILTER_SANITIZE_STRING),
                    'phone' => $this->request->getVar('customer_phone', FILTER_SANITIZE_STRING),
                    'address' => $this->request->getVar('customer_address', FILTER_SANITIZE_STRING),
                    'status' => 'Active',
                    'code' => 'CUS' . date('YmdHis')
                ];
                $this->customerModel->insert($customerData);
                $customerId = $this->customerModel->getInsertID();
            }

            $orderDate = $this->request->getVar('order_date');
            $scheduleDate = $this->request->getVar('schedule_date');

            $subtotalPrice = 0;
            $products = $this->request->getVar('product_id');
            $qtys = $this->request->getVar('qty');
            $prices = $this->request->getVar('price'); // Optional if taking from input, else we query product

            // Validate Stock first
            $productsWithStock = $this->productModel->getProductsWithEffectiveStock();
            $stockMap = [];
            foreach ($productsWithStock as $p) {
                $stockMap[$p->id] = $p->qty;
            }

            for ($i = 0; $i < count($products); $i++) {
                $productId = $products[$i];
                $qty = $qtys[$i];
                $product = $this->productModel->find($productId);
                $available = $stockMap[$productId] ?? 0;
                if ($product && $qty > $available) {
                    $this->db->transRollback();
                    return redirect()->back()->with('error', "Ordered quantity for {$product->name} exceeds available hold stock ({$available}).")->withInput();
                }
            }

            $detailsData = [];

            for ($i = 0; $i < count($products); $i++) {
                $productId = $products[$i];
                $qty = $qtys[$i];
                $price = isset($prices[$i]) ? $prices[$i] : 0;
                $cogs = 0;

                if ($price == 0 || $cogs == 0) {
                    $product = $this->productModel->find($productId);
                    if ($product) {
                        $price = $product->price;
                        $cogs = $product->cogs;
                    }
                }

                $subtotal = $qty * $price;
                $subtotalPrice += $subtotal;

                $detailsData[] = [
                    'product_id' => $productId,
                    'qty' => $qty,
                    'price' => $price,
                    'cogs' => $cogs,
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'total_price' => $subtotal
                ];
            }

            $discountTotal = $this->request->getVar('discount_total') ?? 0;
            $taxTotal = $this->request->getVar('tax_total') ?? 0;
            $totalAmount = ($subtotalPrice - $discountTotal) + $taxTotal;

            // Process Single Payment (Simplified UI)
            $paymentMethodId = $this->request->getVar('payment_method_id');
            $paymentAmount = floatval($this->request->getVar('payment_amount'));
            $paymentReference = $this->request->getVar('payment_reference');
            $paymentNote = $this->request->getVar('payment_note');
            $paymentProofFile = $this->request->getFile('payment_proof');

            $paidAmount = 0;
            $paymentsData = [];

            if ($paymentAmount > 0) {
                $paidAmount = $paymentAmount;

                $proofName = null;
                if ($paymentProofFile && $paymentProofFile->isValid() && !$paymentProofFile->hasMoved()) {
                    $proofName = $paymentProofFile->getRandomName();
                    $paymentProofFile->move('uploads/payments/', $proofName);
                }

                $paymentsData[] = [
                    'method_id' => $paymentMethodId,
                    'amount' => $paymentAmount,
                    'status' => 'paid',
                    'paid_at' => date('Y-m-d H:i:s'),
                    'payment_proof' => $proofName,
                    'payment_reference' => $paymentReference ?? null,
                    'note' => $paymentNote ?? null,
                ];
            }

            // Determine Payment Status
            $paymentStatus = 'unpaid';
            if ($paidAmount > 0) {
                if ($paidAmount >= $totalAmount) {
                    $paymentStatus = 'paid';
                } else {
                    $paymentStatus = 'partial';
                }
            }

            $transactionData = [
                'customer_id' => $customerId,
                'code' => $this->model->generateTransactionCode(),
                'status' => $this->request->getVar('status') ?? 'pending',
                'payment_status' => $this->request->getVar('payment_status') ?? $paymentStatus,
                'order_date' => $orderDate,
                'schedule_date' => $scheduleDate,
                'subtotal_price' => $subtotalPrice,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'refund_amount' => 0,
                'note' => $this->request->getVar('note', FILTER_SANITIZE_STRING),
            ];

            $this->model->insert($transactionData);
            $transactionId = $this->model->getInsertID();

            foreach ($detailsData as &$dd) {
                $dd['transaction_id'] = $transactionId;
            }

            if (!empty($detailsData)) {
                $this->detailModel->insertBatch($detailsData);

                // Permanent Stock Deduction if status is completed
                if (($this->request->getVar('status') ?? 'pending') === 'completed') {
                    foreach ($detailsData as $nd) {
                        $p = $this->productModel->find($nd['product_id']);
                        if ($p) {
                            $this->productModel->update($p->id, ['qty' => $p->qty - $nd['qty']]);
                        }
                    }
                }
            }

            foreach ($paymentsData as &$pd) {
                $pd['transaction_id'] = $transactionId;
            }

            if (!empty($paymentsData)) {
                $this->paymentModel->insertBatch($paymentsData);
            }

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('transactions.create_error') ?? 'Failed to create transaction')->withInput();
            }

            $this->db->transCommit();

            return redirect()->with('success', temp_lang('transactions.create_success') ?? 'Transaction created successfully')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }
}
