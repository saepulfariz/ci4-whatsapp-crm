<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Broadcasts extends BaseController
{
    private $model;
    private $model_variable;

    private $link = 'broadcasts';
    private $view = 'broadcasts';
    private $title = 'Broadcasts';

    public function __construct()
    {
        $this->title = temp_lang('broadcasts.broadcasts');
        $this->model = new \App\Models\BroadcastModel();
        $this->model_variable = new \App\Models\BroadcastVariableModel();
    }

    public function index()
    {
        $redirect = checkPermission('broadcasts.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $data = [
            'title' => $this->title,
            'link'  => $this->link,
            'broadcasts' => $this->model->orderBy('id', 'desc')->findAll()
        ];

        return view($this->view . '/index', $data);
    }

    public function new()
    {
        $redirect = checkPermission('broadcasts.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $data = [
            'title' => $this->title,
            'link'  => $this->link,
        ];

        return view($this->view . '/new', $data);
    }

    public function create()
    {
        $redirect = checkPermission('broadcasts.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $data = [
            'title'   => $this->request->getVar('title'),
            'content' => $this->request->getVar('content'),
        ];

        if (!$this->model->insert($data)) {
            return redirect()->back()->with('error', temp_lang('broadcasts.create_error'))->withInput();
        }

        return redirect()->to($this->link)->with('success', temp_lang('broadcasts.create_success'));
    }

    public function edit($id = null)
    {
        $redirect = checkPermission('broadcasts.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $broadcast = $this->model->find($id);
        if (!$broadcast) return redirect()->to($this->link);

        $data = [
            'title'     => $this->title,
            'link'      => $this->link,
            'broadcast' => $broadcast,
            'variables' => $this->model_variable->where('broadcast_id', $id)->findAll()
        ];

        return view($this->view . '/edit', $data);
    }

    public function update($id = null)
    {
        $redirect = checkPermission('broadcasts.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        if (!$this->model->find($id)) return redirect()->to($this->link);

        $data = [
            'title'   => $this->request->getVar('title'),
            'content' => $this->request->getVar('content'),
        ];

        if (!$this->model->update($id, $data)) {
            return redirect()->back()->with('error', temp_lang('broadcasts.update_error'))->withInput();
        }

        return redirect()->to($this->link)->with('success', temp_lang('broadcasts.update_success'));
    }

    public function delete($id = null)
    {
        $redirect = checkPermission('broadcasts.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        if (!$this->model->find($id)) return redirect()->to($this->link);

        $this->db->transBegin();
        try {
            // Delete dependent variables naturally handled safely
            $this->model_variable->where('broadcast_id', $id)->delete();
            $this->model->delete($id);
            $this->db->transCommit();
            return redirect()->to($this->link)->with('success', temp_lang('broadcasts.delete_success'));
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->to($this->link)->with('error', $e->getMessage());
        }
    }
}
