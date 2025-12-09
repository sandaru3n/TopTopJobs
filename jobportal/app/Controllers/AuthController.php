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
                'profile_picture' => $user['profile_picture'] ?? null,
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
            $userType = $this->session->get('user_type');
            if ($userType === 'admin') {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/manage-jobs');
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
                    'profile_picture' => $user['profile_picture'] ?? null,
                    'is_logged_in' => true,
                ];
                $this->session->set($sessionData);

                // Redirect to appropriate page based on user type
                $redirectUrl = $this->getRedirectUrl();
                return redirect()->to($redirectUrl)
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

        // Redirect regular users to manage-jobs
        return '/manage-jobs';
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

    /**
     * Show profile page
     */
    public function profile(): string
    {
        // Check if user is logged in
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in to access your profile.');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')
                ->with('error', 'User not found.');
        }

        return view('auth/profile', ['user' => $user]);
    }

    /**
     * Update profile (name and profile picture)
     */
    public function updateProfile()
    {
        // Check if user is logged in
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in to update your profile.');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')
                ->with('error', 'User not found.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'first_name' => 'permit_empty|max_length[100]',
            'last_name'  => 'permit_empty|max_length[100]',
            'profile_picture' => 'permit_empty|uploaded[profile_picture]|max_size[profile_picture,2048]|ext_in[profile_picture,png,jpg,jpeg,gif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $updateData = [];

        // Update name
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        
        if ($firstName !== null) {
            $updateData['first_name'] = $firstName;
        }
        if ($lastName !== null) {
            $updateData['last_name'] = $lastName;
        }

        // Handle profile picture upload
        $file = $this->request->getFile('profile_picture');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Create uploads directory if it doesn't exist
            $uploadPath = FCPATH . 'uploads/profile_pictures/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            // Store relative path for database
            $updateData['profile_picture'] = base_url('uploads/profile_pictures/' . $newName);

            // Delete old profile picture if exists
            if (!empty($user['profile_picture']) && file_exists(str_replace(base_url(), FCPATH, $user['profile_picture']))) {
                $oldPicturePath = str_replace(base_url(), FCPATH, $user['profile_picture']);
                if (file_exists($oldPicturePath)) {
                    @unlink($oldPicturePath);
                }
            }
        }

        // Update user
        if (!empty($updateData)) {
            // Skip validation for profile updates (name and picture don't need strict validation)
            $this->userModel->skipValidation(true);
            
            // Update user data
            $this->userModel->update($userId, $updateData);

            // Update session if name changed
            if (isset($updateData['first_name']) || isset($updateData['last_name'])) {
                $updatedUser = $this->userModel->find($userId);
                $this->session->set([
                    'first_name' => $updatedUser['first_name'],
                    'last_name' => $updatedUser['last_name'],
                ]);
            }

            // Update session if profile picture changed
            if (isset($updateData['profile_picture'])) {
                $this->session->set('profile_picture', $updateData['profile_picture']);
            }
        }

        return redirect()->to('/profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword()
    {
        // Check if user is logged in
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in to change your password.');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')
                ->with('error', 'User not found.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Current password is incorrect.');
        }

        // Update password (will be hashed by UserModel callback)
        $this->userModel->skipValidation(false);
        $this->userModel->update($userId, ['password' => $newPassword]);

        return redirect()->to('/profile')
            ->with('success', 'Password changed successfully!');
    }
}

