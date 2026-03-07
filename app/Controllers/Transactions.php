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

class Transactions extends BaseController
{
    private $model;
    private $detailModel;
    private $customerModel;
    private $productModel;
    private $paymentMethodModel;
    private $paymentModel;
    private $paymentRefundModel;

    private $link = 'transactions';
    private $view = 'transactions';
    private $title = 'Transactions';

    public function __construct()
    {
        $this->title = temp_lang('transactions.transactions') ?? 'Transactions';
        $this->model = new TransactionModel();
        $this->detailModel = new TransactionDetailModel();
        $this->customerModel = new CustomerModel();
        $this->productModel = new ProductModel();
        $this->paymentMethodModel = new PaymentMethodModel();
        $this->paymentModel = new PaymentModel();
        $this->paymentRefundModel = new PaymentRefundModel();
    }

    public function index()
    {
        $redirect = checkPermission('transactions.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $transactions = $this->model->select('transactions.*, customers.name as customer_name')
            ->join('customers', 'customers.id = transactions.customer_id')
            ->orderBy('transactions.id', 'desc')
            ->findAll();

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'transactions' => $transactions
        ];

        return view($this->view . '/index', $data);
    }

    public function show($id = null)
    {
        $redirect = checkPermission('transactions.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $transaction = $this->model->select('transactions.*, customers.name as customer_name, customers.phone, customers.address')
            ->join('customers', 'customers.id = transactions.customer_id')
            ->find($id);

        if (!$transaction) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $details = $this->detailModel->select('transaction_details.*, products.name as product_name')
            ->join('products', 'products.id = transaction_details.product_id')
            ->where('transaction_id', $id)
            ->findAll();

        $data = [
            'title' => $this->title . ' Detail',
            'link' => $this->link,
            'transaction' => $transaction,
            'details' => $details,
            'payments' => $this->paymentModel->select('payments.*, payment_methods.name as method_name')
                ->join('payment_methods', 'payment_methods.id = payments.method_id')
                ->where('transaction_id', $id)->findAll(),
            'refunds' => $this->paymentRefundModel->select('payment_refunds.*, payment_methods.name as method_name')
                ->join('payment_methods', 'payment_methods.id = payment_refunds.method_id')
                ->where('transaction_id', $id)->findAll(),
        ];

        return view($this->view . '/show', $data);
    }

    public function new()
    {
        $redirect = checkPermission('transactions.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'customers' => $this->customerModel->where('is_active', 1)->findAll(),
            'products' => $this->productModel->where('is_active', 1)->findAll(),
            'paymentMethods' => $this->paymentMethodModel->findAll(),
        ];

        return view($this->view . '/new', $data);
    }

    public function create()
    {
        $redirect = checkPermission('transactions.create');
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
                $customerData = [
                    'name' => $this->request->getVar('customer_name', FILTER_SANITIZE_STRING),
                    'phone' => $this->request->getVar('customer_phone', FILTER_SANITIZE_STRING),
                    'address' => $this->request->getVar('customer_address', FILTER_SANITIZE_STRING),
                    'is_active' => 1,
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
            for ($i = 0; $i < count($products); $i++) {
                $productId = $products[$i];
                $qty = $qtys[$i];
                $product = $this->productModel->find($productId);
                if ($product && $qty > $product->qty) {
                    $this->db->transRollback();
                    return redirect()->back()->with('error', "Ordered quantity for {$product->name} exceeds available stock ({$product->qty}).")->withInput();
                }
            }

            $detailsData = [];

            for ($i = 0; $i < count($products); $i++) {
                $productId = $products[$i];
                $qty = $qtys[$i];
                $price = isset($prices[$i]) ? $prices[$i] : 0;

                if ($price == 0) {
                    $product = $this->productModel->find($productId);
                    if ($product) $price = $product->price;
                }

                $subtotal = $qty * $price;
                $subtotalPrice += $subtotal;

                $detailsData[] = [
                    'product_id' => $productId,
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'total_price' => $subtotal
                ];
            }

            $discountTotal = $this->request->getVar('discount_total') ?? 0;
            $taxTotal = $this->request->getVar('tax_total') ?? 0;
            $totalAmount = ($subtotalPrice - $discountTotal) + $taxTotal;

            // Process Payments
            $paymentMethodIds = $this->request->getVar('payment_method_id');
            $paymentAmounts = $this->request->getVar('payment_amount');
            $paymentReferences = $this->request->getVar('payment_reference');
            $paymentNotes = $this->request->getVar('payment_note');
            $paymentProofFiles = $this->request->getFiles();

            $paidAmount = 0;
            $paymentsData = [];

            if ($paymentMethodIds) {
                for ($i = 0; $i < count($paymentMethodIds); $i++) {
                    $amt = floatval($paymentAmounts[$i]);
                    if ($amt > 0) {
                        $paidAmount += $amt;
                        
                        // Handle Optional Payment Proof Upload
                        $proofName = null;
                        if (isset($paymentProofFiles['payment_proof'][$i]) && $paymentProofFiles['payment_proof'][$i]->isValid() && !$paymentProofFiles['payment_proof'][$i]->hasMoved()) {
                            $proofName = $paymentProofFiles['payment_proof'][$i]->getRandomName();
                            $paymentProofFiles['payment_proof'][$i]->move('uploads/payments/', $proofName);
                        }

                        $paymentsData[] = [
                            'method_id' => $paymentMethodIds[$i],
                            'amount' => $amt,
                            'status' => 'paid',
                            'paid_at' => date('Y-m-d H:i:s'),
                            'payment_proof' => $proofName,
                            'payment_reference' => $paymentReferences[$i] ?? null,
                            'note' => $paymentNotes[$i] ?? null,
                        ];
                    }
                }
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

    public function edit($id = null)
    {
        $redirect = checkPermission('transactions.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $transaction = $this->model->find($id);

        if (!$transaction) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $details = $this->detailModel->where('transaction_id', $id)->findAll();

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'transaction' => $transaction,
            'details' => $details,
            'customers' => $this->customerModel->where('is_active', 1)->findAll(),
            'products' => $this->productModel->where('is_active', 1)->findAll(),
            'paymentMethods' => $this->paymentMethodModel->findAll(),
            'payments' => $this->paymentModel->where('transaction_id', $id)->findAll(),
            'refunds' => $this->paymentRefundModel->where('transaction_id', $id)->findAll(),
        ];

        return view($this->view . '/edit', $data);
    }

    public function update($id = null)
    {
        $redirect = checkPermission('transactions.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $transaction = $this->model->find($id);

        if (!$transaction) {
            return redirect()->to($this->link);
        }

        $input = $this->request->getVar();
        $isNewCustomer = isset($input['is_new_customer']) && $input['is_new_customer'] == '1';

        $rules = [
            'order_date' => 'required',
            'product_id.*' => 'required',
            // 'qty.*' => 'required|numeric',
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
                $customerData = [
                    'name' => $this->request->getVar('customer_name', FILTER_SANITIZE_STRING),
                    'phone' => $this->request->getVar('customer_phone', FILTER_SANITIZE_STRING),
                    'address' => $this->request->getVar('customer_address', FILTER_SANITIZE_STRING),
                    'is_active' => 1,
                ];
                $this->customerModel->insert($customerData);
                $customerId = $this->customerModel->getInsertID();
            }

            $orderDate = $this->request->getVar('order_date');
            $scheduleDate = $this->request->getVar('schedule_date') ?: $transaction->schedule_date;
            $deliveryDate = $this->request->getVar('delivery_date') ?: $transaction->delivery_date;

            $subtotalPrice = 0;
            $products = $this->request->getVar('product_id');
            $qtys = $this->request->getVar('qty');
            $prices = $this->request->getVar('price');


            // Find old details to offset the stock check
            $oldDetails = $this->detailModel->where('transaction_id', $id)->findAll();
            $oldQtys = [];
            foreach ($oldDetails as $od) {
                $oldQtys[$od->product_id] = $od->qty;
            }

            if ($products) {
                for ($i = 0; $i < count($products); $i++) {
                    $productId = $products[$i];
                    $qty = $qtys[$i];

                    $product = $this->productModel->find($productId);
                    if ($product) {
                        $previouslyOrdered = isset($oldQtys[$productId]) ? $oldQtys[$productId] : 0;
                        $availableStock = $product->qty + $previouslyOrdered;

                        if ($qty > $availableStock) {
                            $this->db->transRollback();
                            return redirect()->back()->with('error', "Ordered quantity for {$product->name} exceeds available stock ({$availableStock}).")->withInput();
                        }
                    }
                }
            }

            $detailsData = [];

            if ($products) {
                for ($i = 0; $i < count($products); $i++) {
                    $productId = $products[$i];
                    $qty = $qtys[$i];
                    $price = isset($prices[$i]) ? $prices[$i] : 0;

                    if ($price == 0) {
                        $product = $this->productModel->find($productId);
                        if ($product) $price = $product->price;
                    }

                    $subtotal = $qty * $price;
                    $subtotalPrice += $subtotal;

                    $detailsData[] = [
                        'transaction_id' => $id,
                        'product_id' => $productId,
                        'qty' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'discount_amount' => 0,
                        'total_price' => $subtotal
                    ];
                }
            }

            $discountTotal = $this->request->getVar('discount_total') ?? $transaction->discount_total;
            $taxTotal = $this->request->getVar('tax_total') ?? $transaction->tax_total;
            $totalAmount = ($subtotalPrice - $discountTotal) + $taxTotal;

            // Process Payments
            $paymentMethodIds = $this->request->getVar('payment_method_id') ?? [];
            $paymentAmounts = $this->request->getVar('payment_amount') ?? [];
            $paymentDates = $this->request->getVar('payment_date') ?? [];
            $paymentReferences = $this->request->getVar('payment_reference') ?? [];
            $paymentNotes = $this->request->getVar('payment_note') ?? [];
            $paymentProofFiles = $this->request->getFiles();
            
            // Need existing payments to retain old proofs if not overwritten
            $existingPayments = $this->paymentModel->where('transaction_id', $id)->findAll();

            $paidAmount = 0;
            $paymentsData = [];

            if ($paymentMethodIds) {
                for ($i = 0; $i < count($paymentMethodIds); $i++) {
                    $amt = floatval($paymentAmounts[$i]);
                    if ($amt > 0) {
                        $paidAmount += $amt;
                        
                        // Handle Optional Payment Proof Upload or Retain Old
                        $proofName = null;
                        if (isset($existingPayments[$i]) && !empty($existingPayments[$i]->payment_proof)) {
                            $proofName = $existingPayments[$i]->payment_proof;
                        }

                        if (isset($paymentProofFiles['payment_proof'][$i]) && $paymentProofFiles['payment_proof'][$i]->isValid() && !$paymentProofFiles['payment_proof'][$i]->hasMoved()) {
                            $proofName = $paymentProofFiles['payment_proof'][$i]->getRandomName();
                            $paymentProofFiles['payment_proof'][$i]->move('uploads/payments/', $proofName);
                        }

                        $paymentsData[] = [
                            'transaction_id' => $id,
                            'method_id' => $paymentMethodIds[$i],
                            'amount' => $amt,
                            'status' => 'paid',
                            'paid_at' => !empty($paymentDates[$i]) ? date('Y-m-d H:i:s', strtotime($paymentDates[$i])) : date('Y-m-d H:i:s'),
                            'payment_proof' => $proofName,
                            'payment_reference' => $paymentReferences[$i] ?? null,
                            'note' => $paymentNotes[$i] ?? null,
                        ];
                    }
                }
            }

            // Process Refunds
            $refundMethodIds = $this->request->getVar('refund_method_id') ?? [];
            $refundAmounts = $this->request->getVar('refund_amount') ?? [];
            $refundReasons = $this->request->getVar('refund_reason') ?? [];
            $refundReferences = $this->request->getVar('refund_reference') ?? [];

            $refundAmount = 0;
            $refundsData = [];

            if ($refundMethodIds) {
                for ($i = 0; $i < count($refundMethodIds); $i++) {
                    $amt = floatval($refundAmounts[$i]);
                    if ($amt > 0) {
                        $refundAmount += $amt;
                        $refundsData[] = [
                            'transaction_id' => $id,
                            'method_id' => $refundMethodIds[$i],
                            'amount' => $amt,
                            'reason' => $refundReasons[$i] ?? 'Customer Refund',
                            'refund_reference' => $refundReferences[$i] ?? null,
                        ];
                    }
                }
            }

            // Determine Payment Status
            $paymentStatus = 'unpaid';
            if ($refundAmount > 0 && $refundAmount >= $paidAmount) {
                $paymentStatus = 'refunded';
            } elseif ($paidAmount > 0) {
                if ($paidAmount >= $totalAmount) {
                    $paymentStatus = 'paid';
                } else {
                    $paymentStatus = 'partial';
                }
            }

            $transactionData = [
                'customer_id' => $customerId,
                'status' => $this->request->getVar('status') ?? $transaction->status,
                'payment_status' => $this->request->getVar('payment_status') ?? $paymentStatus,
                'order_date' => $orderDate,
                'schedule_date' => $scheduleDate,
                'delivery_date' => $deliveryDate,
                'subtotal_price' => $subtotalPrice,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'refund_amount' => $refundAmount,
                'note' => $this->request->getVar('note', FILTER_SANITIZE_STRING) ?? $transaction->note,
            ];

            $this->model->update($id, $transactionData);

            // Delete old details and insert new ones
            $this->detailModel->where('transaction_id', $id)->delete();
            if (!empty($detailsData)) {
                $this->detailModel->insertBatch($detailsData);
            }

            // Delete old payments and insert new ones
            $this->paymentModel->where('transaction_id', $id)->delete();
            if (!empty($paymentsData)) {
                $this->paymentModel->insertBatch($paymentsData);
            }

            // Delete old refunds and insert new ones
            $this->paymentRefundModel->where('transaction_id', $id)->delete();
            if (!empty($refundsData)) {
                $this->paymentRefundModel->insertBatch($refundsData);
            }

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('transactions.update_error') ?? 'Failed to update transaction')->withInput();
            }

            $this->db->transCommit();

            return redirect()->with('success', temp_lang('transactions.update_success') ?? 'Transaction updated successfully')->to($this->link);
        } catch (\Throwable $th) {
            log_message('info', 'error disini');
            $this->db->transRollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete($id = null)
    {
        $redirect = checkPermission('transactions.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $transaction = $this->model->find($id);

        if (!$transaction) {
            return redirect()->to($this->link);
        }

        $this->db->transBegin();

        try {
            // Because useSoftDeletes might be true, we also delete details.
            $this->detailModel->where('transaction_id', $id)->delete();
            $this->model->delete($id);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('transactions.delete_error') ?? 'Failed to delete transaction')->withInput();
            }

            $this->db->transCommit();

            return redirect()->with('success', temp_lang('transactions.delete_success') ?? 'Transaction deleted successfully')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }
}
