<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ChatBotModel;

class ChatBots extends BaseController
{
    private $model;
    private $autoReplyModel;
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

    public function webhook()
    {
        // 1. Ambil RAW body
        $rawBody = $this->request->getBody();

        // 2. Ambil API key dari query
        $apiKey = $this->request->getGet('apikey');

        if (!$apiKey) {
            return $this->response
                ->setStatusCode(400)
                ->setBody('Invalid apikey');
        }

        // 3. Validasi API key
        if ($apiKey !== getenv('WEBHOOK_API_KEY')) {
            return $this->response
                ->setStatusCode(400)
                ->setBody('Invalid apikey');
        }

        // 4. Decode JSON
        $data = json_decode($rawBody, true);

        if (!$data || !isset($data['event'])) {
            return $this->response
                ->setStatusCode(400)
                ->setBody('Invalid payload');
        }

        // 5. Ambil secret untuk HMAC
        $secret = getenv('GOWA_HMAC_SECRET');

        // Ambil header signature
        $signature = $this->request->getHeaderLine('X-Hub-Signature-256');

        $hash = 'sha256=' . hash_hmac('sha256', $rawBody, $secret);

        /*
        if (!hash_equals($hash, $signature)) {
            log_message('error', 'Invalid signature');
            log_message('error', 'Hash: ' . $hash);
            log_message('error', 'Headers: ' . json_encode($this->request->headers()));

            return $this->response
                ->setStatusCode(401)
                ->setBody('Invalid signature');
        }
        */

        // 6. Proses event
        if ($data['event'] === 'message') {

            $payload = $data['payload'] ?? [];

            $from = $payload['from'] ?? '';
            $name = $payload['from_name'] ?? '';
            $text = $payload['body'] ?? '';

            // get all  data from auto_replies and check is_exact_match true use ==, else use str_contains
            $autoReply = $this->autoReplyModel->findAll();
            $answer = '';
            if ($autoReply) {
                foreach ($autoReply as $reply) {
                    if ($reply['is_exact_match'] == 1) {
                        if (strtolower($reply['keyword']) == strtolower($text)) {
                            send_message($from, $reply['content']);
                            $answer = $reply['content'];
                            break;
                        }
                    } else {
                        if (str_contains($text, $reply['keyword'])) {
                            send_message($from, $reply['content']);
                            $answer = $reply['content'];
                            break;
                        }
                    }
                }
            }

            // save message to database to model chat_bots
            $this->model->insert([
                'from' => $from,
                'name' => $name,
                'question' => $text,
                'answer' => $answer,
            ]);
        }

        // 7. Response WAJIB 200
        return $this->response
            ->setStatusCode(200)
            ->setBody('OK');
    }
}
