<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BroadcastModel;
use App\Models\BroadcastVariableModel;
use App\Models\BroadcastLogModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;

class ShareBroadcasts extends BaseController
{
    private $model;
    private $broadcastModel;
    private $variableModel;
    private $customerModel;
    private $productModel;

    private $link = 'share-broadcasts';
    private $view = 'share_broadcasts';
    private $title = 'Share Broadcasts';

    public function __construct()
    {
        $this->model = new BroadcastLogModel();
        $this->broadcastModel = new BroadcastModel();
        $this->variableModel = new BroadcastVariableModel();
        $this->customerModel = new CustomerModel();
        $this->productModel = new ProductModel();

        $this->title = temp_lang('broadcasts.share_broadcasts') ?? 'Share Broadcasts';
    }

    public function index()
    {
        $redirect = checkPermission('share-broadcasts.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'logs' => $this->model->select('broadcast_logs.*, broadcasts.title as template_title, customers.name as customer_name')
                ->join('broadcasts', 'broadcasts.id = broadcast_logs.broadcast_id', 'left')
                ->join('customers', 'customers.id = broadcast_logs.customer_id', 'left')
                ->orderBy('broadcast_logs.id', 'desc')
                ->findAll()
        ];

        return view($this->view . '/index', $data);
    }

    public function new()
    {
        $redirect = checkPermission('share-broadcasts.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'templates' => $this->broadcastModel->findAll(),
            'customers' => $this->customerModel->where('is_active', 1)->findAll(),
        ];

        return view($this->view . '/new', $data);
    }

    public function get_variables($id)
    {
        $variables = $this->variableModel->where('broadcast_id', $id)->findAll();
        $template = $this->broadcastModel->find($id);

        return $this->response->setJSON([
            'variables' => $variables,
            'content' => $template ? $template->content : ''
        ]);
    }

    public function create()
    {
        $redirect = checkPermission('share-broadcasts.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $templateId = $this->request->getVar('broadcast_id');
        $customerIds = $this->request->getVar('customer_ids') ?? [];
        $customPhones = $this->request->getVar('custom_phones') ?? [];
        $variableValues = $this->request->getVar('vars') ?? [];

        $template = $this->broadcastModel->find($templateId);
        if (!$template) {
            return redirect()->back()->with('error', 'Template not found')->withInput();
        }

        $contentBase = $template->content;

        $this->db->transBegin();

        // get all products qty and hold qty 
        $products = $this->productModel->getAllProductQty();

        foreach ($products as $key => &$product) {
            $product->no = $key + 1;
        }

        $parser = \Config\Services::parser();

        $data_parser = [];

        $data_parser['products'] = $products;


        try {
            // Process Customers
            foreach ($customerIds as $customerId) {
                $customer = $this->customerModel->find($customerId);
                if ($customer) {
                    $data_parser['client_name'] = $customer->name;
                    $data_parser['client_phone'] = $customer->phone;
                    $data_parser['client_address'] = $customer->address;

                    $contentResult = $parser->setData($data_parser)->renderString($contentBase, ['cascadeData' => true]);


                    $finalContent = $contentResult;
                    // foreach ($variableValues as $varName => $value) {
                    //     $finalContent = str_replace('{' . $varName . '}', $value, $finalContent);
                    // }

                    $this->model->insert([
                        'broadcast_id' => $templateId,
                        'customer_id' => $customerId,
                        'content' => $finalContent,
                        'to' => $customer->phone,
                        'status' => 'pending'
                    ]);

                    // if 08 change to 628 and end with @s.whatsapp.net
                    $phone = $customer->phone;
                    if (strpos($phone, '08') === 0) {
                        $phone = '628' . substr($phone, 2) . getenv('GOWA_PHONE');
                    } else {
                        // check if not string in customer phone getenv('GOWA_PHONE')
                        if (strpos($phone, getenv('GOWA_PHONE')) === false) {
                            $phone = $phone . getenv('GOWA_PHONE');
                        }
                    }

                    log_message('error', json_encode($phone));

                    $result = send_message($phone, $finalContent);
                    log_message('error', json_encode($result));
                    // result {"code":"SUCCESS","message":"Message sent to

                    if (isset($result['code']) && $result['code'] === 'SUCCESS') {
                        $this->model->update($this->model->getInsertID(), [
                            'status' => 'sent'
                        ]);
                    }
                }
            }

            // Process Custom Phones
            foreach ($customPhones as $customPhone) {
                if (!empty($customPhone)) {
                    $finalContent = $contentBase;
                    foreach ($variableValues as $varName => $value) {
                        $finalContent = str_replace('{' . $varName . '}', $value, $finalContent);
                    }

                    $this->model->insert([
                        'broadcast_id' => $templateId,
                        'customer_id' => null,
                        'content' => $finalContent,
                        'to' => $customPhone,
                        'status' => 'pending'
                    ]);

                    $phoneTarget = $customPhone;
                    if (strpos($phoneTarget, '08') === 0) {
                        $phoneTarget = '628' . substr($phoneTarget, 2) . getenv('GOWA_PHONE');
                    } else {
                        // check if not string in customer phone getenv('GOWA_PHONE')
                        if (strpos($phoneTarget, getenv('GOWA_PHONE')) === false) {
                            $phoneTarget = $phoneTarget . getenv('GOWA_PHONE');
                        }
                    }
                    $result = send_message($phoneTarget, $finalContent);
                    if (isset($result['code']) && $result['code'] === 'SUCCESS') {
                        $this->model->update($this->model->getInsertID(), [
                            'status' => 'sent'
                        ]);
                    }
                }
            }

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to save broadcast logs')->withInput();
            }

            $this->db->transCommit();
            return redirect()->to($this->link)->with('success', 'Broadcasts scheduled successfully');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function reshare($id)
    {
        $redirect = checkPermission('share-broadcasts.reshare');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $log = $this->model->find($id);
        if (!$log) {
            return redirect()->back()->with('error', 'Log entry not found');
        }

        // Duplicate the log with pending status
        $data = [
            'broadcast_id' => $log->broadcast_id,
            'customer_id' => $log->customer_id,
            'content' => $log->content,
            'to' => $log->to,
            'status' => 'pending'
        ];

        if (strpos($log->to, '08') === 0) {
            $log->to = '628' . substr($log->to, 2) . getenv('GOWA_PHONE');
            // log_message('debug', 'Phone number changed to ' . $log->to);
        } else {
            // check if not string in customer phone getenv('GOWA_PHONE')
            if (strpos($log->to, getenv('GOWA_PHONE')) === false) {
                $log->to = $log->to . getenv('GOWA_PHONE');
            }
        }

        $result = send_message($log->to, $log->content);
        log_message('debug', 'Result: ' . json_encode($result));


        if ($this->model->insert($data)) {

            if (isset($result['code']) && $result['code'] === 'SUCCESS') {
                log_message('debug', 'Broadcast sent successfully');
                $this->model->update($this->model->getInsertID(), [
                    'status' => 'sent'
                ]);
            }

            return redirect()->to($this->link)->with('success', 'Broadcast re-scheduled successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to reshare broadcast');
        }
    }
}
