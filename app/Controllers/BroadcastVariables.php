<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class BroadcastVariables extends BaseController
{
    private $model;
    private $model_broadcast;

    private $link = 'broadcast-variables';
    private $view = 'broadcast_variables';
    private $title = 'Broadcast Variables';

    public function __construct()
    {
        $this->title = temp_lang('broadcasts.broadcast_variables');
        $this->model = new \App\Models\BroadcastVariableModel();
        $this->model_broadcast = new \App\Models\BroadcastModel();
    }

    public function index()
    {
        $redirect = checkPermission('broadcasts.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $broadcast_id = $this->request->getVar('broadcast_id');
        if (!$broadcast_id) return redirect()->to('broadcasts');

        $broadcast = $this->model_broadcast->find($broadcast_id);
        if (!$broadcast) return redirect()->to('broadcasts');

        $data = [
            'title'        => $this->title . ' - ' . $broadcast->title,
            'link'         => $this->link,
            'broadcast_id' => $broadcast_id,
            'variables'    => $this->model->where('broadcast_id', $broadcast_id)->orderBy('id', 'desc')->findAll()
        ];

        return view($this->view . '/index', $data);
    }

    public function new()
    {
        $redirect = checkPermission('broadcasts.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $broadcast_id = $this->request->getVar('broadcast_id');
        if (!$broadcast_id) return redirect()->to('broadcasts');

        $data = [
            'title'        => $this->title,
            'link'         => $this->link,
            'broadcast_id' => $broadcast_id,
        ];

        return view($this->view . '/new', $data);
    }

    public function create()
    {
        $redirect = checkPermission('broadcasts.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $broadcast_id = $this->request->getVar('broadcast_id');

        $data = [
            'broadcast_id' => $broadcast_id,
            'name'         => $this->request->getVar('name'),
        ];

        if (!$this->model->insert($data)) {
            return redirect()->back()->with('error', temp_lang('broadcasts.var_create_error'))->withInput();
        }

        return redirect()->to($this->link . '?broadcast_id=' . $broadcast_id)->with('success', temp_lang('broadcasts.var_create_success'));
    }

    public function edit($id = null)
    {
        $redirect = checkPermission('broadcasts.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $variable = $this->model->find($id);
        if (!$variable) return redirect()->to('broadcasts');

        $data = [
            'title'        => $this->title,
            'link'         => $this->link,
            'variable'     => $variable,
            'broadcast_id' => $variable->broadcast_id,
        ];

        return view($this->view . '/edit', $data);
    }

    public function update($id = null)
    {
        $redirect = checkPermission('broadcasts.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $variable = $this->model->find($id);
        if (!$variable) return redirect()->to('broadcasts');

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        if (!$this->model->update($id, $data)) {
            return redirect()->back()->with('error', temp_lang('broadcasts.var_update_error'))->withInput();
        }

        return redirect()->to($this->link . '?broadcast_id=' . $variable->broadcast_id)->with('success', temp_lang('broadcasts.var_update_success'));
    }

    public function delete($id = null)
    {
        $redirect = checkPermission('broadcasts.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) return $redirect;

        $variable = $this->model->find($id);
        if (!$variable) return redirect()->to('broadcasts');

        $broadcast_id = $variable->broadcast_id;

        try {
            $this->model->delete($id);
            return redirect()->to($this->link . '?broadcast_id=' . $broadcast_id)->with('success', temp_lang('broadcasts.var_delete_success'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
