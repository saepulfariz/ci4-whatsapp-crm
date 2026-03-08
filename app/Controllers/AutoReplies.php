<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AutoReplyModel;
use App\Entities\AutoReply;

class AutoReplies extends BaseController
{
    private $model;
    private $link = 'auto-replies';
    private $view = 'auto_replies';
    private $title = 'Auto Replies';

    public function __construct()
    {
        $this->model = new AutoReplyModel();
        $this->title = temp_lang('auto_replies.title') ?? 'Auto Replies';
    }

    public function index()
    {
        $redirect = checkPermission('auto-replies.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'data' => $this->model->orderBy('id', 'desc')->findAll()
        ];

        return view($this->view . '/index', $data);
    }

    public function new()
    {
        $redirect = checkPermission('auto-replies.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => temp_lang('auto_replies.add_reply') ?? 'Add Auto Reply',
            'link' => $this->link,
            'auto_reply' => new AutoReply(),
        ];

        return view($this->view . '/new', $data);
    }

    public function create()
    {
        $redirect = checkPermission('auto-replies.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $entity = new AutoReply($this->request->getPost());

        if ($this->model->save($entity)) {
            return redirect()->to($this->link)->with('success', temp_lang('auto_replies.reply_created'));
        }

        return redirect()->back()->with('error', temp_lang('auto_replies.reply_error'))->withInput();
    }

    public function edit($id)
    {
        $redirect = checkPermission('auto-replies.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $auto_reply = $this->model->find($id);
        if (!$auto_reply) {
            return redirect()->to($this->link)->with('error', 'Data not found');
        }

        $data = [
            'title' => temp_lang('auto_replies.edit_reply') ?? 'Edit Auto Reply',
            'link' => $this->link,
            'auto_reply' => $auto_reply,
        ];

        return view($this->view . '/edit', $data);
    }

    public function update($id)
    {
        $redirect = checkPermission('auto-replies.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $entity = new AutoReply($this->request->getPost());
        $entity->id = $id;

        if ($this->model->save($entity)) {
            return redirect()->to($this->link)->with('success', temp_lang('auto_replies.reply_updated'));
        }

        return redirect()->back()->with('error', temp_lang('auto_replies.reply_error'))->withInput();
    }

    public function delete($id)
    {
        $redirect = checkPermission('auto-replies.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        if ($this->model->delete($id)) {
            return redirect()->to($this->link)->with('success', temp_lang('auto_replies.reply_deleted'));
        }

        return redirect()->to($this->link)->with('error', temp_lang('auto_replies.reply_error'));
    }
}
