<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\JobModel;

class Home extends BaseController
{
    protected $helpers = ['url'];
    protected $companyModel;
    protected $jobModel;

    public function __construct()
    {
        $this->companyModel = new CompanyModel();
        $this->jobModel = new JobModel();
    }

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

    public function postJob(): string
    {
        return view('home/post-job');
    }

    public function processPostJob()
    {
        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login?redirect=post-job')
                ->with('error', 'Please log in to post a job.');
        }

        // Validate and process the job posting
        // This is a placeholder - implement actual job posting logic here
        $validation = \Config\Services::validation();
        
        $rules = [
            'job_title' => 'required|max_length[255]',
            'application_email' => 'permit_empty|valid_email',
            'application_url' => 'permit_empty|valid_url',
            'location' => 'required|max_length[255]',
            'job_type' => 'required|in_list[full-time,part-time,internship,remote,contract]',
            'application_phone' => 'permit_empty|max_length[20]',
            'monthly_salary' => 'permit_empty|numeric',
            'job_category' => 'required|max_length[100]',
            'min_experience' => 'permit_empty|integer',
            'description' => 'permit_empty',
            'company_name' => 'required|max_length[255]',
            'company_description' => 'permit_empty',
            'company_website' => 'permit_empty|valid_url',
            'company_logo' => 'permit_empty|uploaded[company_logo]|max_size[company_logo,2048]|ext_in[company_logo,png,jpg,jpeg,gif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Validate that at least one application method is provided
        $applicationEmail = $this->request->getPost('application_email');
        $applicationUrl = $this->request->getPost('application_url');
        $applicationPhone = $this->request->getPost('application_phone');
        
        if (empty($applicationEmail) && empty($applicationUrl) && empty($applicationPhone)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please provide at least one application method (Email, URL, or Phone).');
        }

        // Handle file upload
        $logoPath = null;
        $file = $this->request->getFile('company_logo');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Create uploads directory in public folder if it doesn't exist
            $uploadPath = FCPATH . 'uploads/company_logos/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Generate unique filename
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            
            // Store relative path for database (accessible via web)
            $logoPath = base_url('uploads/company_logos/' . $newName);
        }

        try {
            // Get form data
            $companyName = $this->request->getPost('company_name');
            $companyWebsite = $this->request->getPost('company_website');
            $companyDescription = $this->request->getPost('company_description');
            $jobTitle = $this->request->getPost('job_title');
            $location = $this->request->getPost('location');
            $jobType = $this->request->getPost('job_type');
            $jobCategory = $this->request->getPost('job_category');
            $description = $this->request->getPost('description');
            $monthlySalary = $this->request->getPost('monthly_salary');
            $minExperience = $this->request->getPost('min_experience');
            $applicationEmail = $this->request->getPost('application_email');
            $applicationUrl = $this->request->getPost('application_url');
            $applicationPhone = $this->request->getPost('application_phone');
            $userId = session()->get('user_id');

            // Find or create company
            $companyData = [
                'website' => $companyWebsite ?: null,
                'description' => $companyDescription ?: null,
                'industry' => $jobCategory ?: null,
            ];
            
            if ($logoPath) {
                $companyData['logo'] = $logoPath;
            }
            
            $company = $this->companyModel->findOrCreate($companyName, $companyData);
            $companyId = $company['id'];

            // Build job description
            $fullDescription = $description ?: '';
            
            // Add application contact information to description (for compatibility and display)
            $contactInfo = [];
            if ($applicationEmail) {
                $contactInfo[] = "Application Email: {$applicationEmail}";
            }
            if ($applicationUrl) {
                $contactInfo[] = "Apply Online: {$applicationUrl}";
            }
            if ($applicationPhone) {
                $contactInfo[] = "Phone: {$applicationPhone}";
            }
            
            if (!empty($contactInfo)) {
                $fullDescription .= "\n\n--- Application Information ---\n" . implode("\n", $contactInfo);
            }

            // Map experience
            $experienceLevel = 'junior';
            if ($minExperience) {
                $experienceLevel = $this->jobModel->mapExperienceLevel((int)$minExperience);
            }

            // Calculate salary (convert monthly to yearly if needed, or store as monthly)
            $salaryMin = null;
            $salaryMax = null;
            if ($monthlySalary) {
                $salaryMin = (float)$monthlySalary;
                $salaryMax = (float)$monthlySalary;
            }

            // Determine if remote
            $isRemote = ($jobType === 'remote' || stripos($location, 'remote') !== false);

            // Generate temporary slug first (will be updated with ID after insert)
            $tempSlug = $this->jobModel->generateSlug($companyName, $jobTitle);

            // Prepare job data
            $jobData = [
                'company_id' => $companyId,
                'title' => $jobTitle,
                'slug' => $tempSlug, // Temporary slug
                'description' => trim($fullDescription),
                'job_type' => $jobType,
                'experience_level' => $experienceLevel,
                'min_experience' => $minExperience ? (int)$minExperience : 0,
                'salary_min' => $salaryMin,
                'salary_max' => $salaryMax,
                'salary_currency' => 'USD',
                'salary_period' => 'monthly',
                'is_salary_disclosed' => $monthlySalary ? 1 : 0,
                'location' => $location,
                'is_remote' => $isRemote ? 1 : 0,
                'skills_required' => $jobCategory, // Store category as skills for now
                'status' => 'active',
                'posted_by' => $userId,
                'posted_at' => date('Y-m-d H:i:s'),
            ];
            
            // Add application contact fields if they exist in database
            // CodeIgniter will ignore these fields if columns don't exist
            if ($applicationEmail) {
                $jobData['application_email'] = $applicationEmail;
            }
            if ($applicationUrl) {
                $jobData['application_url'] = $applicationUrl;
            }
            if ($applicationPhone) {
                $jobData['application_phone'] = $applicationPhone;
            }

            // Insert job
            $jobId = $this->jobModel->insert($jobData);
            
            if (!$jobId) {
                throw new \Exception('Failed to create job');
            }

            // Generate and update slug with job ID
            $finalSlug = $this->jobModel->generateSlug($companyName, $jobTitle, $jobId);
            $this->jobModel->update($jobId, ['slug' => $finalSlug]);

            return redirect()->to('/jobs')
                ->with('success', 'Job posted successfully!');
                
        } catch (\Exception $e) {
            log_message('error', 'Job posting error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while posting the job. Please try again.');
        }
    }
}
