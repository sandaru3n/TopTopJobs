<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $helpers = ['url'];

    public function index(): string
    {
        return view('home/index');
    }

    public function jobs(): string
    {
        return view('home/jobs');
    }

    public function jobDetails($slug = null): string
    {
        // If slug is provided, extract ID from it
        // Slug format: company-title-id
        if ($slug) {
            // Extract ID from end of slug (e.g., "google-senior-product-designer-1" -> 1)
            if (preg_match('/-(\d+)$/', $slug, $matches)) {
                $jobId = (int)$matches[1];
                // Pass the ID to the view via query parameter
                $_GET['id'] = $jobId;
            }
        }
        
        return view('home/job-details');
    }
}
