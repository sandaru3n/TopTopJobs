<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    protected $helpers = ['url'];
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    /**
     * Admin Dashboard
     */
    public function dashboard(): string
    {
        $data = [
            'title' => 'Admin Dashboard',
            'user' => [
                'name' => $this->session->get('first_name') . ' ' . $this->session->get('last_name'),
                'email' => $this->session->get('email'),
                'user_type' => $this->session->get('user_type'),
            ]
        ];

        return view('admin/dashboard', $data);
    }
}

