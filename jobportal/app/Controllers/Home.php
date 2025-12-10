<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\JobModel;
use App\Models\SavedJobModel;

class Home extends BaseController
{
    protected $helpers = ['url'];
    protected $companyModel;
    protected $jobModel;
    protected $savedJobModel;

    public function __construct()
    {
        $this->companyModel = new CompanyModel();
        $this->jobModel = new JobModel();
        $this->savedJobModel = new SavedJobModel();
    }

    public function index()
    {
        // Allow both logged-in and guest users to access the home page
        return view('home/index');
    }

    /**
     * Dashboard - Redirect to appropriate dashboard based on user type
     */
    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in to access the dashboard.');
        }

        $userType = session()->get('user_type');
        
        // Redirect admin users to admin dashboard
        if ($userType === 'admin') {
            return redirect()->to('/admin/dashboard');
        }
        
        // For regular users, redirect to home or manage jobs
        return redirect()->to('/manage-jobs');
    }

    public function jobs(): string
    {
        return view('home/jobs');
    }

    /**
     * Manage Jobs - List user's posted jobs
     */
    public function manageJobs(): string
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in to manage your jobs.');
        }

        $userId = session()->get('user_id');
        
        // Get user's jobs
        $jobs = $this->jobModel->where('posted_by', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Get company info for each job
        foreach ($jobs as &$job) {
            $company = $this->companyModel->find($job['company_id']);
            $job['company_name'] = $company['name'] ?? 'Unknown Company';
            $logo = $company['logo'] ?? null;
            
            // Convert HTTP URLs to HTTPS to prevent mixed content warnings
            if ($logo && strpos($logo, 'http://') === 0) {
                $logo = str_replace('http://', 'https://', $logo);
            }
            
            $job['company_logo'] = $logo;
        }

        return view('home/manage-jobs', ['jobs' => $jobs]);
    }

    /**
     * Edit Job - Show edit form
     */
    public function editJob($id = null): string
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in to edit jobs.');
        }

        // Get ID from URI if not provided as parameter
        if ($id === null) {
            $segments = $this->request->getUri()->getSegments();
            $id = (int)end($segments);
        } else {
            $id = (int)$id;
        }

        if (!$id) {
            return redirect()->to('/manage-jobs')
                ->with('error', 'Invalid job ID.');
        }

        $userId = session()->get('user_id');
        $job = $this->jobModel->find($id);

        // Check if job exists and belongs to user
        if (!$job || $job['posted_by'] != $userId) {
            return redirect()->to('/manage-jobs')
                ->with('error', 'Job not found or you do not have permission to edit it.');
        }

        // Get company info
        $company = $this->companyModel->find($job['company_id']);
        
        // Convert HTTP URLs to HTTPS to prevent mixed content warnings
        if ($company && isset($company['logo']) && $company['logo'] && strpos($company['logo'], 'http://') === 0) {
            $company['logo'] = str_replace('http://', 'https://', $company['logo']);
        }

        // Extract application info from description if stored there
        $description = $job['description'] ?? '';
        $applicationEmail = $job['application_email'] ?? null;
        $applicationUrl = $job['application_url'] ?? null;
        $applicationPhone = $job['application_phone'] ?? null;

        // If not in dedicated fields, try to extract from description
        if (!$applicationEmail && !$applicationUrl && !$applicationPhone) {
            if (strpos($description, '--- Application Information ---') !== false) {
                $parts = explode('--- Application Information ---', $description);
                $description = trim($parts[0]);
                $contactInfo = isset($parts[1]) ? trim($parts[1]) : '';
                
                // Extract email
                if (preg_match('/Application Email:\s*(.+)/i', $contactInfo, $matches)) {
                    $applicationEmail = trim($matches[1]);
                }
                // Extract URL
                if (preg_match('/Application URL:\s*(.+)/i', $contactInfo, $matches)) {
                    $applicationUrl = trim($matches[1]);
                }
                // Extract phone
                if (preg_match('/Application Phone:\s*(.+)/i', $contactInfo, $matches)) {
                    $applicationPhone = trim($matches[1]);
                }
            }
        }

        // Extract category from skills_required
        $jobCategory = null;
        if (!empty($job['skills_required'])) {
            $categoryList = ['Cashier', 'Data Entry', 'IT/Software', 'Marketing', 'Sales', 'Customer Service', 'Design', 'Engineering', 'Finance', 'Healthcare', 'Education', 'Other'];
            $skills = is_string($job['skills_required']) ? explode(',', $job['skills_required']) : [$job['skills_required']];
            if (!empty($skills) && in_array(trim($skills[0]), $categoryList)) {
                $jobCategory = trim($skills[0]);
            }
        }

        // Extract monthly salary
        $monthlySalary = null;
        if (!empty($job['salary_min']) && !empty($job['salary_max'])) {
            if ($job['salary_min'] == $job['salary_max']) {
                $monthlySalary = $job['salary_min'];
            }
        } elseif (!empty($job['salary_min'])) {
            $monthlySalary = $job['salary_min'];
        }

        return view('home/edit-job', [
            'job' => $job,
            'company' => $company,
            'description' => $description,
            'applicationEmail' => $applicationEmail,
            'applicationUrl' => $applicationUrl,
            'applicationPhone' => $applicationPhone,
            'jobCategory' => $jobCategory,
            'monthlySalary' => $monthlySalary,
        ]);
    }

    /**
     * Update Job - Process job update
     */
    public function updateJob($id = null)
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in to update jobs.');
        }

        // Get ID from URI if not provided as parameter
        if ($id === null) {
            $segments = $this->request->getUri()->getSegments();
            $id = (int)end($segments);
        } else {
            $id = (int)$id;
        }

        if (!$id) {
            return redirect()->to('/manage-jobs')
                ->with('error', 'Invalid job ID.');
        }

        $userId = session()->get('user_id');
        $job = $this->jobModel->find($id);

        // Check if job exists and belongs to user
        if (!$job || $job['posted_by'] != $userId) {
            return redirect()->to('/manage-jobs')
                ->with('error', 'Job not found or you do not have permission to edit it.');
        }

        // Validation rules (similar to post job)
        $validation = \Config\Services::validation();
        $rules = [
            'job_title' => 'required|max_length[255]',
            'location' => 'required|max_length[255]',
            'job_type' => 'required|in_list[full-time,part-time,contract,internship,remote]',
            'application_email' => 'permit_empty|valid_email',
            'application_url' => 'permit_empty|valid_url',
            'application_phone' => 'permit_empty',
            'monthly_salary' => 'permit_empty|numeric',
            'job_category' => 'permit_empty',
            'min_experience' => 'permit_empty|integer',
            'description' => 'permit_empty',
            'valid_through' => 'required|valid_date',
            'company_name' => 'required|max_length[255]',
            'company_description' => 'permit_empty',
            'company_website' => 'permit_empty|valid_url',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Validate valid_through is today or in the future
        $validThroughInput = $this->request->getPost('valid_through');
        $validThroughDate = date('Y-m-d', strtotime($validThroughInput));
        $today = date('Y-m-d');
        if ($validThroughDate < $today) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Valid Through date must be today or in the future.');
        }

        // Get form data
        $jobTitle = $this->request->getPost('job_title');
        $description = $this->request->getPost('description');
        $location = $this->request->getPost('location');
        $jobType = $this->request->getPost('job_type');
        $jobCategory = $this->request->getPost('job_category');
        $monthlySalary = $this->request->getPost('monthly_salary') ?: null;
        $minExperience = $this->request->getPost('min_experience');
        $applicationEmail = $this->request->getPost('application_email');
        $applicationUrl = $this->request->getPost('application_url');
        $applicationPhone = $this->request->getPost('application_phone');
        $isRemote = $this->request->getPost('is_remote') == '1' || $this->request->getPost('is_remote') == 1;
        $validThroughInput = $this->request->getPost('valid_through');
        $companyName = $this->request->getPost('company_name');
        $companyDescription = $this->request->getPost('company_description');
        $companyWebsite = $this->request->getPost('company_website');

        // Handle company logo upload
        $logoPath = null;
        $logoFile = $this->request->getFile('company_logo');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/company_logos/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $newName = $logoFile->getRandomName();
            $logoFile->move($uploadPath, $newName);
            $logoPath = base_url('uploads/company_logos/' . $newName);
            // Ensure HTTPS for uploaded images to prevent mixed content warnings
            if (strpos($logoPath, 'http://') === 0) {
                $logoPath = str_replace('http://', 'https://', $logoPath);
            }
        }

        // Update or find company
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
        
        // Add application contact information to description
        $contactInfo = [];
        if ($applicationEmail) {
            $contactInfo[] = "Application Email: {$applicationEmail}";
        }
        if ($applicationUrl) {
            $contactInfo[] = "Application URL: {$applicationUrl}";
        }
        if ($applicationPhone) {
            $contactInfo[] = "Application Phone: {$applicationPhone}";
        }
        
        if (!empty($contactInfo)) {
            $fullDescription .= "\n\n--- Application Information ---\n" . implode("\n", $contactInfo);
        }

        // Generate slug
        $slug = $this->jobModel->generateSlug($companyName, $jobTitle, $id);

        // Prepare job data
        $jobData = [
            'company_id' => $companyId,
            'title' => $jobTitle,
            'slug' => $slug,
            'description' => $fullDescription,
            'location' => $location,
            'job_type' => $jobType,
            'experience_level' => $minExperience ? $this->jobModel->mapExperienceLevel($minExperience) : 'junior',
            'min_experience' => $minExperience ? (int)$minExperience : 0,
            'is_remote' => $isRemote ? 1 : 0,
            'expires_at' => date('Y-m-d H:i:s', strtotime($validThroughInput)),
        ];

        // Add salary if provided
        if ($monthlySalary) {
            $jobData['salary_min'] = (float)$monthlySalary;
            $jobData['salary_max'] = (float)$monthlySalary;
            $jobData['salary_currency'] = 'USD';
            $jobData['salary_period'] = 'monthly';
        }

        // Add skills/category
        if ($jobCategory) {
            $jobData['skills_required'] = $jobCategory;
        }

        // Add application fields if they exist in the database
        if ($applicationEmail) {
            $jobData['application_email'] = $applicationEmail;
        }
        if ($applicationUrl) {
            $jobData['application_url'] = $applicationUrl;
        }
        if ($applicationPhone) {
            $jobData['application_phone'] = $applicationPhone;
        }

        // Update job
        $this->jobModel->update($id, $jobData);

        return redirect()->to('/manage-jobs')
            ->with('success', 'Job updated successfully!');
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
            'salary_min' => 'permit_empty|numeric',
            'salary_max' => 'permit_empty|numeric',
            'job_category' => 'required|max_length[100]',
            'min_experience' => 'permit_empty|integer',
            'description' => 'permit_empty',
            'responsibilities' => 'permit_empty',
            'requirements' => 'permit_empty',
            'skills' => 'permit_empty|max_length[500]',
            'is_remote' => 'permit_empty|in_list[0,1]',
            'valid_through' => 'required|valid_date',
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

        // Validate salary range
        $salaryMin = $this->request->getPost('salary_min');
        $salaryMax = $this->request->getPost('salary_max');
        if ($salaryMin && $salaryMax && (float)$salaryMin > (float)$salaryMax) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Minimum salary cannot be greater than maximum salary.');
        }

        // Validate valid_through is present and not in the past
        $validThroughInput = $this->request->getPost('valid_through');
        if (empty($validThroughInput)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please provide an expiration date (Valid Through).');
        }
        $validThroughTs = strtotime($validThroughInput);
        if ($validThroughTs === false || $validThroughTs < strtotime('today')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Expiration date must be today or in the future.');
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
            // Ensure HTTPS for uploaded images to prevent mixed content warnings
            if (strpos($logoPath, 'http://') === 0) {
                $logoPath = str_replace('http://', 'https://', $logoPath);
            }
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
            $responsibilities = $this->request->getPost('responsibilities');
            $requirements = $this->request->getPost('requirements');
            $skills = $this->request->getPost('skills');
            $salaryMin = $this->request->getPost('salary_min');
            $salaryMax = $this->request->getPost('salary_max');
            $minExperience = $this->request->getPost('min_experience');
            $applicationEmail = $this->request->getPost('application_email');
            $applicationUrl = $this->request->getPost('application_url');
            $applicationPhone = $this->request->getPost('application_phone');
            $isRemote = $this->request->getPost('is_remote') == '1' || $this->request->getPost('is_remote') == 1;
            $validThroughInput = $this->request->getPost('valid_through');
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

            // Process salary range
            $finalSalaryMin = null;
            $finalSalaryMax = null;
            if ($salaryMin) {
                $finalSalaryMin = (float)$salaryMin;
            }
            if ($salaryMax) {
                $finalSalaryMax = (float)$salaryMax;
            }
            // If only one salary value provided, use it for both min and max
            if ($finalSalaryMin && !$finalSalaryMax) {
                $finalSalaryMax = $finalSalaryMin;
            } elseif ($finalSalaryMax && !$finalSalaryMin) {
                $finalSalaryMin = $finalSalaryMax;
            }

            // Determine if remote (from checkbox or job type)
            if (!$isRemote) {
                $isRemote = ($jobType === 'remote' || stripos($location, 'remote') !== false);
            }

            // Process skills (comma-separated string)
            $skillsString = null;
            if ($skills) {
                $skillsArray = array_map('trim', explode(',', $skills));
                $skillsString = implode(',', array_filter($skillsArray));
            }

            // Valid through date
            $validThrough = null;
            if (!empty($validThroughInput)) {
                $validThrough = date('Y-m-d H:i:s', strtotime($validThroughInput));
            }

            // Generate temporary slug first (will be updated with ID after insert)
            $tempSlug = $this->jobModel->generateSlug($companyName, $jobTitle);

            // Prepare job data
            $jobData = [
                'company_id' => $companyId,
                'title' => $jobTitle,
                'slug' => $tempSlug, // Temporary slug
                'description' => trim($fullDescription),
                'responsibilities' => $responsibilities ? trim($responsibilities) : null,
                'requirements' => $requirements ? trim($requirements) : null,
                'job_type' => $jobType,
                'experience_level' => $experienceLevel,
                'min_experience' => $minExperience ? (int)$minExperience : 0,
                'salary_min' => $finalSalaryMin,
                'salary_max' => $finalSalaryMax,
                'salary_currency' => 'USD',
                'salary_period' => 'monthly',
                'is_salary_disclosed' => ($finalSalaryMin || $finalSalaryMax) ? 1 : 0,
                'location' => $location,
                'is_remote' => $isRemote ? 1 : 0,
                'skills_required' => $skillsString ?: $jobCategory, // Use skills if provided, otherwise use category
                'status' => 'active',
                'posted_by' => $userId,
                'posted_at' => date('Y-m-d H:i:s'),
                'expires_at' => $validThrough,
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

    /**
     * Saved Jobs - List user's saved jobs
     */
    public function savedJobs(): string
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in to view your saved jobs.');
        }

        $userId = session()->get('user_id');
        
        // Get saved job IDs
        $savedJobs = $this->savedJobModel->getSavedJobs($userId);
        
        if (empty($savedJobs)) {
            return view('home/saved-jobs', ['jobs' => []]);
        }

        // Get job IDs
        $jobIds = array_column($savedJobs, 'job_id');
        
        // Get full job details
        $jobs = $this->jobModel->whereIn('id', $jobIds)
            ->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Get company info for each job
        foreach ($jobs as &$job) {
            $company = $this->companyModel->find($job['company_id']);
            $job['company_name'] = $company['name'] ?? 'Unknown Company';
            $logo = $company['logo'] ?? null;
            
            // Convert HTTP URLs to HTTPS to prevent mixed content warnings
            if ($logo && strpos($logo, 'http://') === 0) {
                $logo = str_replace('http://', 'https://', $logo);
            }
            
            $job['company_logo'] = $logo;
            $job['saved_at'] = $savedJobs[array_search($job['id'], $jobIds)]['created_at'] ?? null;
        }

        return view('home/saved-jobs', ['jobs' => $jobs]);
    }

    /**
     * Toggle Save Job - API endpoint to save/unsave a job
     */
    public function toggleSaveJob()
    {
        // Set JSON response header
        $this->response->setContentType('application/json');
        
        try {
            // Check if user is logged in
            if (!session()->get('user_id')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please log in to save jobs.'
                ])->setStatusCode(401);
            }

            $userId = session()->get('user_id');
            
            // Get job_id from POST or JSON body
            $jobId = null;
            
            // Try POST first
            $jobId = $this->request->getPost('job_id');
            
            // If not in POST, try JSON body
            if (!$jobId) {
                try {
                    $json = $this->request->getJSON(true);
                    if ($json && isset($json['job_id'])) {
                        $jobId = $json['job_id'];
                    }
                } catch (\Exception $e) {
                    // If getJSON fails, try raw body
                    $rawInput = $this->request->getBody();
                    if (!empty($rawInput)) {
                        $json = json_decode($rawInput, true);
                        if ($json && isset($json['job_id'])) {
                            $jobId = $json['job_id'];
                        }
                    }
                }
            }

            if (!$jobId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Job ID is required.'
                ])->setStatusCode(400);
            }

            $jobId = (int)$jobId;

            // Check if job exists
            $job = $this->jobModel->find($jobId);
            if (!$job) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Job not found.'
                ])->setStatusCode(404);
            }

            // Toggle save status
            $isSaved = $this->savedJobModel->isSaved($userId, $jobId);
            
            if ($isSaved) {
                // Unsave
                $result = $this->savedJobModel->unsaveJob($userId, $jobId);
                if ($result) {
                    return $this->response->setJSON([
                        'success' => true,
                        'saved' => false,
                        'message' => 'Job unsaved successfully.'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to unsave job.'
                    ])->setStatusCode(500);
                }
            } else {
                // Save
                $result = $this->savedJobModel->saveJob($userId, $jobId);
                if ($result) {
                    return $this->response->setJSON([
                        'success' => true,
                        'saved' => true,
                        'message' => 'Job saved successfully.'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to save job.'
                    ])->setStatusCode(500);
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Toggle save job error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Check if job is saved - API endpoint
     */
    public function checkSavedJob($jobId = null)
    {
        // Set JSON response header
        $this->response->setContentType('application/json');
        
        try {
            // Check if user is logged in
            if (!session()->get('user_id')) {
                return $this->response->setJSON([
                    'success' => true,
                    'saved' => false
                ]);
            }

            $userId = session()->get('user_id');
            
            // Get job ID from route parameter or query string
            if ($jobId === null || $jobId === '') {
                // Try to get from URL segments
                $segments = $this->request->getUri()->getSegments();
                // The route is api/check-saved-job/{id}, so the ID should be the last segment
                if (end($segments) !== 'check-saved-job' && is_numeric(end($segments))) {
                    $jobId = (int)end($segments);
                } else {
                    // Try query parameter
                    $jobId = $this->request->getGet('job_id');
                }
            }
            
            // Convert to integer
            $jobId = (int)$jobId;

            if (!$jobId || $jobId <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Job ID is required.'
                ])->setStatusCode(400);
            }

            $isSaved = $this->savedJobModel->isSaved($userId, $jobId);

            return $this->response->setJSON([
                'success' => true,
                'saved' => $isSaved
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Check saved job error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred.',
                'saved' => false
            ])->setStatusCode(500);
        }
    }
}
