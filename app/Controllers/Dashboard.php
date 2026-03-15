<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index(): string
    {
        // echo 'Login  - ' . auth()->user()->email . ' - <a href="' . base_url('logout') . '">logout</a> - ';
        $data = [
            'title' => 'Dashboard',
        ];
        return view('dashboard/index', $data);
    }
}
