<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ChatBotModel;

class ChatBots extends BaseController
{
    private $model;
    private $link = 'chat-bots';
    private $view = 'chat_bots';
    private $title = 'Chat Bots';

    public function __construct()
    {
        $this->model = new ChatBotModel();
        $this->title = temp_lang('chat_bots.title') ?? 'Chat Bots';
    }

    public function index()
    {
        $redirect = checkPermission('chat-bots.access');
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
}
