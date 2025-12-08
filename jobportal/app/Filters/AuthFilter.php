<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Check if user is logged in
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/login')
                ->with('error', 'Please login to access this page.');
        }

        // Check role if specified
        if (!empty($arguments)) {
            $userType = $session->get('user_type');
            
            if (!in_array($userType, $arguments)) {
                return redirect()->to('/')
                    ->with('error', 'You do not have permission to access this page.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

