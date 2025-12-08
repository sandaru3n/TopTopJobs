<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    /**
     * Redirect if user is already logged in
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        if ($session->get('is_logged_in')) {
            $userType = $session->get('user_type');
            
            switch ($userType) {
                case 'admin':
                    return redirect()->to('/admin/dashboard');
                case 'employer':
                    return redirect()->to('/employer/dashboard');
                default:
                    return redirect()->to('/');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

