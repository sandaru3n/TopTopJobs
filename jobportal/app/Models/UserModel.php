<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields   = true;
    protected $allowedFields    = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'profile_picture',
        'resume',
        'user_type',
        'status',
        'email_verified',
        'email_verified_at',
        'last_login'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'email'      => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password'   => 'required|min_length[6]',
        'first_name' => 'permit_empty|max_length[100]',
        'last_name'  => 'permit_empty|max_length[100]',
        'user_type'  => 'required|in_list[job_seeker,employer,admin]',
    ];

    protected $validationMessages = [
        'email' => [
            'required'    => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique'   => 'This email is already registered',
        ],
        'password' => [
            'required'   => 'Password is required',
            'min_length' => 'Password must be at least 6 characters',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Verify user credentials
     */
    public function verifyCredentials(string $email, string $password)
    {
        $user = $this->where('email', $email)
                     ->where('status', 'active')
                     ->first();

        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            return $user;
        }

        return false;
    }

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(int $userId, string $role): bool
    {
        $user = $this->find($userId);
        return $user && $user['user_type'] === $role;
    }
}

