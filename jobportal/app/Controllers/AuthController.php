<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class AuthController extends BaseController
{
    protected $helpers = ['url', 'form'];
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Show login page
     */
    public function login(): string
    {
        // Redirect if already logged in
        if ($this->session->get('user_id')) {
            return redirect()->to($this->getRedirectUrl());
        }

        return view('auth/login');
    }

    /**
     * Process login
     */
    public function processLogin()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $user = $this->userModel->verifyCredentials($email, $password);

        if ($user) {
            // Set session data
            $sessionData = [
                'user_id'    => $user['id'],
                'email'      => $user['email'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'user_type'  => $user['user_type'],
                'is_logged_in' => true,
            ];

            $this->session->set($sessionData);

            // Set remember me cookie if checked
            if ($remember) {
                $this->setRememberMeCookie($user['id']);
            }

            $redirectUrl = $this->getRedirectUrl();
            return redirect()->to($redirectUrl)
                ->with('success', 'Welcome back, ' . ($user['first_name'] ?? $user['email']) . '!');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Invalid email or password');
    }

    /**
     * Show signup page
     */
    public function signup(): string
    {
        // Redirect if already logged in
        if ($this->session->get('user_id')) {
            return redirect()->to('/');
        }

        return view('auth/signup');
    }

    /**
     * Process signup
     */
    public function processSignup()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'first_name' => 'permit_empty|max_length[100]',
            'last_name'  => 'permit_empty|max_length[100]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'phone'      => $this->request->getPost('phone'),
            'user_type'  => 'user', // Always 'user' for regular signups
            'status'     => 'active',
        ];

        try {
            $userId = $this->userModel->insert($data);

            if ($userId) {
                // Auto login after signup
                $user = $this->userModel->find($userId);
                $sessionData = [
                    'user_id'    => $user['id'],
                    'email'      => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name'],
                    'user_type'  => $user['user_type'],
                    'is_logged_in' => true,
                ];
                $this->session->set($sessionData);

                return redirect()->to('/')
                    ->with('success', 'Account created successfully! Welcome to TopTopJobs!');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create account. Please try again.');
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->destroy();
        $this->clearRememberMeCookie();

        return redirect()->to('/login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Get redirect URL based on user type
     */
    private function getRedirectUrl(): string
    {
        $userType = $this->session->get('user_type');

        if ($userType === 'admin') {
            return '/admin/dashboard';
        }

        // Default to home for regular users
        return '/';
    }

    /**
     * Set remember me cookie
     */
    private function setRememberMeCookie(int $userId)
    {
        $token = bin2hex(random_bytes(32));
        $expires = time() + (30 * 24 * 60 * 60); // 30 days

        // Store token in database (you might want to create a remember_tokens table)
        // For now, we'll just set a cookie
        setcookie('remember_token', $token, $expires, '/', '', false, true);
    }

    /**
     * Clear remember me cookie
     */
    private function clearRememberMeCookie()
    {
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
    }
}

