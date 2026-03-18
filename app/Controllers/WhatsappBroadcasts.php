<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class WhatsappBroadcasts extends BaseController
{
    private $model;
    private $model_reception;
    private $model_customer;
    private $model_customer_group;

    private $link = 'whatsapp-broadcasts';
    private $view = 'whatsapp-broadcasts';
    private $title = 'Whatsapp Broadcasts';

    public function __construct()
    {
        $this->title = temp_lang('whatsapp_broadcasts.whatsapp_broadcasts');
        $this->model = new \App\Models\WhatsappBroadcastModel();
        $this->model_reception = new \App\Models\WhatsappBroadcastReceptionModel();
        $this->model_customer = new \App\Models\CustomerModel();
        $this->model_customer_group = new \App\Models\GroupModel();
    }

    public function index()
    {
        $redirect = checkPermission('whatsapp-broadcasts.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $data = [
            'title' => $this->title,
            'link'  => $this->link,
            'broadcasts' => $this->model->select('whatsapp_broadcasts.*, users.username as created_by')->join('users', 'users.id = whatsapp_broadcasts.cid', 'left')->orderBy('id', 'desc')->findAll(),
            'broadcast_receptions' => $this->model_reception->orderBy('id', 'desc')->findAll(),
            'customers' => $this->model_customer->select('customers.*, groups.name as group')->join('groups', 'groups.id = customers.group_id', 'left')->findAll(),
            'groups' => $this->model_customer_group->select('groups.*, count(customers.id) as total_member')->join('customers', 'customers.group_id = groups.id', 'left')->groupBy('groups.id')->orderBy('id', 'desc')->findAll(),
        ];

        return view($this->view . '/new', $data);
    }


    public function create()
    {
        $redirect = checkPermission('whatsapp-broadcasts.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $recipientType = $this->request->getVar('recipient_type');
        $groupId = $this->request->getVar('group_id');
        $selectedCustomers = $this->request->getVar('selected_customers');
        $title = $this->request->getVar('title');
        $content = $this->request->getVar('content');

        $recipients = [];

        if ($recipientType === 'all') {
            $customers = $this->model_customer->where('status', 'Active')->findAll();
            foreach ($customers as $customer) {
                $recipients[] = [
                    'customer_id' => $customer->id,
                    'to' => $customer->phone,
                ];
            }
        } elseif ($recipientType === 'group' && !empty($groupId)) {
            $customers = $this->model_customer->where('group_id', $groupId)->where('status', 'Active')->findAll();
            foreach ($customers as $customer) {
                $recipients[] = [
                    'customer_id' => $customer->id,
                    'to' => $customer->phone,
                ];
            }
        } elseif ($recipientType === 'selected' && !empty($selectedCustomers)) {
            $ids = explode(',', $selectedCustomers);
            $customers = $this->model_customer->whereIn('id', $ids)->findAll();
            foreach ($customers as $customer) {
                $recipients[] = [
                    'customer_id' => $customer->id,
                    'to' => $customer->phone,
                ];
            }
        }

        if (empty($recipients)) {
            return redirect()->back()->with('error', 'No recipients found.')->withInput();
        }

        $fileName = null;
        $file = $this->request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/broadcasts', $fileName);
        }

        $dataBroadcast = [
            'type'            => $recipientType,
            'title'           => $title,
            'content'         => $content,
            'total_recipient' => count($recipients),
            'file'            => $fileName,
            'status'          => 'Pending',
        ];

        $this->db->transBegin();

        if (!$this->model->insert($dataBroadcast)) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to create broadcast.')->withInput();
        }

        $broadcastId = $this->model->getInsertID();
        $receptionModel = new \App\Models\WhatsappBroadcastReceptionModel();

        foreach ($recipients as $recipient) {

            $phone = $recipient['to'];

            // Normalize phone number
            if (strpos($phone, '08') === 0) {
                $phone = '628' . substr($phone, 2) . getenv('GOWA_PHONE');
            } elseif (strpos($phone, getenv('GOWA_PHONE')) === false) {
                $phone = $phone . getenv('GOWA_PHONE');
            }

            $receptionModel->insert([
                'whatsapp_broadcast_id' => $broadcastId,
                'customer_id'           => $recipient['customer_id'],
                'to'                    => $phone,
                'file'                  => $fileName,
                'content'               => $content,
                'status'                => 'pending',
            ]);

            if ($fileName) {
                $result = send_whatsapp_image($phone, $content, WRITEPATH . 'uploads/broadcasts/' . $fileName);
            } else {
                $result = send_whatsapp_message($phone, $content);
            }
            log_message('error', json_encode($result));

            if (isset($result['code']) && $result['code'] === 'SUCCESS') {
                $receptionModel->update($receptionModel->getInsertID(), ['status' => 'Sent']);

                // update status broadcast
                $this->model->update($broadcastId, ['status' => 'Sent']);
            } else {
                $receptionModel->update($receptionModel->getInsertID(), ['status' => 'Failed']);

                // update status broadcast
                $this->model->update($broadcastId, ['status' => 'Failed']);
            }
        }

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to save recipients.')->withInput();
        }

        $this->db->transCommit();

        return redirect()->to(base_url($this->link))->with('success', 'Broadcast created and ready to be sent.');
    }
}
