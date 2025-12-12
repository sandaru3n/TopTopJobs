<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\JobModel;
use App\Models\SavedJobModel;
use App\Models\CollectionModel;
use App\Models\CategoryModel;
use App\Models\SubcategoryModel;

class Home extends BaseController
{
    protected $helpers = ['url'];
    protected $companyModel;
    protected $jobModel;
    protected $savedJobModel;
    protected $collectionModel;
    protected $categoryModel;
    protected $subcategoryModel;

    public function __construct()
    {
        $this->companyModel = new CompanyModel();
        $this->jobModel = new JobModel();
        $this->savedJobModel = new SavedJobModel();
        $this->collectionModel = new CollectionModel();
        $this->categoryModel = new CategoryModel();
        $this->subcategoryModel = new SubcategoryModel();
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
     * Static pages
     */
    public function about(): string
    {
        return view('home/about');
    }

    public function contact(): string
    {
        return view('home/contact');
    }

    public function terms(): string
    {
        return view('home/terms');
    }

    public function privacy(): string
    {
        return view('home/privacy');
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

        // Load image helper
        helper('image');
        
        // Get company info for each job
        foreach ($jobs as &$job) {
            $company = $this->companyModel->find($job['company_id']);
            $job['company_name'] = $company['name'] ?? 'Unknown Company';
            $logo = $company['logo'] ?? null;
            
            // Fix image URL for production
            $job['company_logo'] = fix_image_url($logo);
        }

        return view('home/manage-jobs', ['jobs' => $jobs]);
    }

    /**
     * Delete Job - Delete a job (does not delete the company)
     */
    public function deleteJob($id = null)
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in to delete jobs.');
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
                ->with('error', 'Job not found or you do not have permission to delete it.');
        }

        try {
            // Get job URL before deletion for Google Indexing API
            $jobSlug = $job['slug'] ?? '';
            $jobUrl = null;
            if ($jobSlug) {
                $jobUrl = base_url('job/' . $jobSlug . '/');
            }

            // Delete the job (company is NOT deleted)
            $this->jobModel->delete($id);

            // Notify Google Indexing API that the job URL has been deleted
            if ($jobUrl) {
                try {
                    $indexingService = new \App\Services\GoogleIndexingService();
                    $indexingService->notifyUrlDeleted($jobUrl);
                } catch (\Exception $e) {
                    // Silently fail - don't break job deletion if indexing fails
                    log_message('debug', 'Google Indexing API deletion notification failed: ' . $e->getMessage());
                }
            }

            return redirect()->to('/manage-jobs')
                ->with('success', 'Job deleted successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Job deletion error: ' . $e->getMessage());
            return redirect()->to('/manage-jobs')
                ->with('error', 'An error occurred while deleting the job. Please try again.');
        }
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

        // Load image helper
        helper('image');
        
        // Get company info
        $company = $this->companyModel->find($job['company_id']);
        
        // Fix company logo URL for production
        if ($company && isset($company['logo'])) {
            $company['logo'] = fix_image_url($company['logo']);
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

        // Extract category from skills_required (legacy support)
        $jobCategory = null;
        if (!empty($job['skills_required'])) {
            $categoryList = ['Cashier', 'Data Entry', 'IT/Software', 'Marketing', 'Sales', 'Customer Service', 'Design', 'Engineering', 'Finance', 'Healthcare', 'Education', 'Other'];
            $skills = is_string($job['skills_required']) ? explode(',', $job['skills_required']) : [$job['skills_required']];
            if (!empty($skills) && in_array(trim($skills[0]), $categoryList)) {
                $jobCategory = trim($skills[0]);
            }
        }

        // Extract monthly salary (for backward compatibility with old form, but we now use salary_min/salary_max)
        $monthlySalary = null;
        if (!empty($job['salary_min']) && !empty($job['salary_max'])) {
            if ($job['salary_min'] == $job['salary_max']) {
                $monthlySalary = $job['salary_min'];
            }
        } elseif (!empty($job['salary_min'])) {
            $monthlySalary = $job['salary_min'];
        }

        // Get categories for dropdown
        $categories = $this->categoryModel->where('status', 'active')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        // Get collections for dropdown
        $collections = $this->collectionModel->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();

        // Get current collection ID if job is in a collection
        $currentCollectionId = null;
        if (!empty($job['collection_id'])) {
            $currentCollectionId = $job['collection_id'];
        } else {
            // Check if job is in any collection via collection_jobs table
            $db = \Config\Database::connect();
            $builder = $db->table('collection_jobs');
            $collectionJob = $builder->where('job_id', $id)->get()->getRowArray();
            if ($collectionJob) {
                $currentCollectionId = $collectionJob['collection_id'];
            }
        }

        // Country list for dropdown
        $countryList = [
            'LK' => 'Sri Lanka', 'US' => 'United States', 'GB' => 'United Kingdom',
            'CA' => 'Canada', 'AU' => 'Australia', 'IN' => 'India', 'PK' => 'Pakistan',
            'BD' => 'Bangladesh', 'NP' => 'Nepal', 'MY' => 'Malaysia', 'SG' => 'Singapore',
            'PH' => 'Philippines', 'TH' => 'Thailand', 'VN' => 'Vietnam', 'ID' => 'Indonesia',
            'JP' => 'Japan', 'KR' => 'South Korea', 'CN' => 'China', 'DE' => 'Germany',
            'FR' => 'France', 'IT' => 'Italy', 'ES' => 'Spain', 'NL' => 'Netherlands',
            'BE' => 'Belgium', 'CH' => 'Switzerland', 'AT' => 'Austria', 'SE' => 'Sweden',
            'NO' => 'Norway', 'DK' => 'Denmark', 'FI' => 'Finland', 'PL' => 'Poland',
            'IE' => 'Ireland', 'PT' => 'Portugal', 'GR' => 'Greece', 'CZ' => 'Czech Republic',
            'RO' => 'Romania', 'HU' => 'Hungary', 'SK' => 'Slovakia', 'BG' => 'Bulgaria',
            'HR' => 'Croatia', 'SI' => 'Slovenia', 'EE' => 'Estonia', 'LV' => 'Latvia',
            'LT' => 'Lithuania', 'NZ' => 'New Zealand', 'ZA' => 'South Africa', 'AE' => 'UAE',
            'SA' => 'Saudi Arabia', 'EG' => 'Egypt', 'NG' => 'Nigeria', 'KE' => 'Kenya',
            'GH' => 'Ghana', 'BR' => 'Brazil', 'MX' => 'Mexico', 'AR' => 'Argentina',
            'CL' => 'Chile', 'CO' => 'Colombia', 'PE' => 'Peru', 'VE' => 'Venezuela',
        ];

        // Detect country from job data
        $detectedCountry = [
            'country_code' => $job['country_code'] ?? 'LK',
            'country' => $job['country'] ?? 'Sri Lanka'
        ];

        // Fix job image URL if exists
        $jobImageUrl = null;
        if (!empty($job['image'])) {
            $jobImageUrl = fix_image_url($job['image']);
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
            'categories' => $categories,
            'collections' => $collections,
            'currentCollectionId' => $currentCollectionId,
            'countryList' => $countryList,
            'detectedCountry' => $detectedCountry,
            'jobImageUrl' => $jobImageUrl,
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
        $jobType = $this->request->getPost('job_type');
        $isRemoteJob = ($jobType === 'remote');
        
        $rules = [
            'job_title' => 'required|max_length[255]',
            'location' => $isRemoteJob ? 'permit_empty|max_length[255]' : 'required|max_length[255]',
            'job_type' => 'required|in_list[full-time,part-time,contract,internship,remote]',
            'application_email' => 'permit_empty|valid_email',
            'application_url' => 'permit_empty|valid_url',
            'application_phone' => 'permit_empty|max_length[20]',
            'salary_min' => 'permit_empty|numeric',
            'salary_max' => 'permit_empty|numeric',
            'category_id' => 'required|integer',
            'subcategory_id' => 'permit_empty|integer',
            'job_category' => 'permit_empty|max_length[100]', // Keep for backward compatibility
            'min_experience' => 'permit_empty|integer',
            'description' => 'permit_empty',
            'responsibilities' => 'permit_empty',
            'requirements' => 'permit_empty',
            'skills' => 'permit_empty|max_length[500]',
            'valid_through' => 'required|valid_date',
            'company_name' => 'required|max_length[255]',
            'company_description' => 'permit_empty',
            'company_website' => 'permit_empty|valid_url',
            'country' => 'permit_empty|max_length[100]',
            'country_code' => 'permit_empty|max_length[2]',
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
        $jobCategory = $this->request->getPost('job_category'); // Keep for backward compatibility
        $categoryId = $this->request->getPost('category_id');
        $subcategoryId = $this->request->getPost('subcategory_id');
        $salaryMin = $this->request->getPost('salary_min');
        $salaryMax = $this->request->getPost('salary_max');
        $minExperience = $this->request->getPost('min_experience');
        $responsibilities = $this->request->getPost('responsibilities');
        $requirements = $this->request->getPost('requirements');
        $skills = $this->request->getPost('skills');
        $applicationEmail = $this->request->getPost('application_email');
        $applicationUrl = $this->request->getPost('application_url');
        $applicationPhone = $this->request->getPost('application_phone');
        $country = $this->request->getPost('country');
        $countryCode = $this->request->getPost('country_code');
        // Determine if remote from job_type
        $isRemote = ($jobType === 'remote');
        
        // Set default location for remote jobs if location is empty
        if ($isRemote) {
            $location = trim($location ?? '');
            if (empty($location)) {
                $location = 'Remote';
            }
        } else {
            // Ensure location is not empty for non-remote jobs
            $location = trim($location ?? '');
            if (empty($location)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Location is required for non-remote jobs.');
            }
        }
        
        $validThroughInput = $this->request->getPost('valid_through');
        $companyName = $this->request->getPost('company_name');
        $companyId = $this->request->getPost('company_id');
        $companyDescription = $this->request->getPost('company_description');
        $companyWebsite = $this->request->getPost('company_website');
        
        // If company_id is not provided in POST, use the existing job's company_id
        if (empty($companyId) && isset($job['company_id'])) {
            $companyId = $job['company_id'];
        }

        // Handle company logo upload
        $logoPath = null;
        $logoFile = $this->request->getFile('company_logo');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/company_logos/';
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $uploadPath);
                }
            }
            
            // Check if directory is writable
            if (!is_writable($uploadPath)) {
                log_message('error', 'Upload directory is not writable: ' . $uploadPath);
            } else {
                $newName = $logoFile->getRandomName();
                if ($logoFile->move($uploadPath, $newName)) {
                    // Verify file was actually moved
                    $fullPath = $uploadPath . $newName;
                    if (file_exists($fullPath) && is_readable($fullPath)) {
                        // Store relative path in database (e.g., /uploads/company_logos/filename.png)
                        helper('image');
                        $logoPath = upload_path('company_logos/' . $newName);
                        log_message('debug', 'Company logo uploaded successfully: ' . $logoPath);
                    } else {
                        log_message('error', 'File move reported success but file not found: ' . $fullPath);
                    }
                } else {
                    $errors = $logoFile->getErrors();
                    log_message('error', 'Failed to move company logo: ' . implode(', ', $errors));
                }
            }
        }

        // If company_id is provided (existing company selected), use it
        if (!empty($companyId)) {
            $company = $this->companyModel->find($companyId);
            if ($company) {
                // Update company with new data if provided (maps_url is preserved from existing company)
                $companyData = [];
                if ($companyWebsite) $companyData['website'] = $companyWebsite;
                // Keep existing maps_url from database, don't update it
                if ($companyDescription) $companyData['description'] = $companyDescription;
                if ($jobCategory) $companyData['industry'] = $jobCategory;
                if ($logoPath) $companyData['logo'] = $logoPath;
                
                if (!empty($companyData)) {
                    $this->companyModel->update($companyId, $companyData);
                }
                $companyId = $company['id'];
            } else {
                // Company ID not found, fall back to findOrCreate
                $companyId = null;
            }
        }
        
        // If no valid company ID, find or create company
        if (empty($companyId)) {
            $companyData = [
                'website' => $companyWebsite ?: null,
                'description' => $companyDescription ?: null,
                'industry' => $jobCategory ?: null,
            ];
            
            if ($logoPath) {
                $companyData['logo'] = $logoPath;
            }
            
            $company = $this->companyModel->findOrCreate($companyName, $companyData);
            if (!$company || !isset($company['id'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to create or find company. Please try again.');
            }
            $companyId = $company['id'];
        }
        
        // Final check: ensure company_id is set
        if (empty($companyId)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Company ID is required. Please ensure company name is provided.');
        }

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

        // Generate slug only if title or company name changed, otherwise keep existing slug
        $existingSlug = $job['slug'] ?? '';
        $existingTitle = $job['title'] ?? '';
        $existingCompany = null;
        if (!empty($job['company_id'])) {
            $existingCompanyData = $this->companyModel->find($job['company_id']);
            $existingCompany = $existingCompanyData['name'] ?? null;
        }
        
        // Only regenerate slug if title or company name has changed
        if (trim($existingTitle) !== trim($jobTitle) || trim($existingCompany) !== trim($companyName)) {
            $slug = $this->jobModel->generateSlug($companyName, $jobTitle, $id);
        } else {
            // Keep existing slug
            $slug = $existingSlug;
        }

        // Calculate salary values
        $finalSalaryMin = null;
        $finalSalaryMax = null;
        if (!empty($salaryMin) || !empty($salaryMax)) {
            $finalSalaryMin = !empty($salaryMin) ? (float)$salaryMin : null;
            $finalSalaryMax = !empty($salaryMax) ? (float)$salaryMax : null;
            
            // If only one value provided, use it for both min and max
            if ($finalSalaryMin && !$finalSalaryMax) {
                $finalSalaryMax = $finalSalaryMin;
            } elseif ($finalSalaryMax && !$finalSalaryMin) {
                $finalSalaryMin = $finalSalaryMax;
            }
        }

        // Validate salary range (minimum must be less than maximum)
        if ($finalSalaryMin && $finalSalaryMax && $finalSalaryMin >= $finalSalaryMax) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Minimum salary must be less than maximum salary.');
        }

        // Validate that required fields are present
        if (empty($companyId)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Company information is required.');
        }
        
        if (empty($jobTitle)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Job title is required.');
        }
        
        if (empty($location)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Location is required.');
        }
        
        if (empty($categoryId)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Category is required.');
        }
        
        // Prepare job data (only include slug if it has changed)
        $jobData = [
            'company_id' => (int)$companyId,
            'category_id' => (int)$categoryId,
            'subcategory_id' => $subcategoryId ? (int)$subcategoryId : null,
            'title' => trim($jobTitle),
            'description' => trim($fullDescription),
            'responsibilities' => $responsibilities ? trim($responsibilities) : null,
            'requirements' => $requirements ? trim($requirements) : null,
            'location' => trim($location),
            'country' => $country ? trim($country) : null,
            'country_code' => $countryCode ? strtoupper(trim($countryCode)) : null,
            'job_type' => $jobType,
            'experience_level' => $minExperience ? $this->jobModel->mapExperienceLevel($minExperience) : 'junior',
            'min_experience' => $minExperience ? (int)$minExperience : 0,
            'salary_min' => $finalSalaryMin,
            'salary_max' => $finalSalaryMax,
            'salary_currency' => ($finalSalaryMin || $finalSalaryMax) ? 'USD' : null,
            'salary_period' => ($finalSalaryMin || $finalSalaryMax) ? 'monthly' : null,
            'is_salary_disclosed' => ($finalSalaryMin || $finalSalaryMax) ? 1 : 0,
            'is_remote' => $isRemote ? 1 : 0,
            'skills_required' => $skills ? trim($skills) : ($jobCategory ? trim($jobCategory) : null), // Use skills if provided, otherwise use category for backward compatibility
            'expires_at' => date('Y-m-d H:i:s', strtotime($validThroughInput)),
        ];

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

        // Handle job image upload
        $jobImagePath = null;
        $jobImageFile = $this->request->getFile('job_image');
        if ($jobImageFile && $jobImageFile->isValid() && !$jobImageFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/job_images/';
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $uploadPath);
                }
            }
            
            // Check if directory is writable
            if (!is_writable($uploadPath)) {
                log_message('error', 'Upload directory is not writable: ' . $uploadPath);
            } else {
                $newName = $jobImageFile->getRandomName();
                if ($jobImageFile->move($uploadPath, $newName)) {
                    // Verify file was actually moved
                    $fullPath = $uploadPath . $newName;
                    if (file_exists($fullPath) && is_readable($fullPath)) {
                        helper('image');
                        $jobImagePath = upload_path('job_images/' . $newName);
                        log_message('debug', 'Job image uploaded successfully: ' . $jobImagePath);
                    } else {
                        log_message('error', 'File move reported success but file not found: ' . $fullPath);
                    }
                } else {
                    $errors = $jobImageFile->getErrors();
                    log_message('error', 'Failed to move job image: ' . implode(', ', $errors));
                }
            }
        }
        
        // Add job image if uploaded
        if ($jobImagePath) {
            $jobData['image'] = $jobImagePath;
        }

        // Handle collection (if job needs to be added to a collection)
        $collectionId = $this->request->getPost('collection_id');
        if ($collectionId && is_numeric($collectionId)) {
            $collectionId = (int)$collectionId;
            // Check if job is already in a collection, remove old association first
            $db = \Config\Database::connect();
            $builder = $db->table('collection_jobs');
            $builder->where('job_id', $id)->delete();
            // Add to new collection
            $this->collectionModel->addJobToCollection($collectionId, $id);
        }

        // Update job - handle slug validation
        // If slug has changed, add it to jobData and validate; otherwise skip it to avoid validation errors
        if ($slug !== $existingSlug) {
            // Slug has changed, add it to update data and validate uniqueness while excluding current record
            $jobData['slug'] = $slug;
            // Format: is_unique[table.field,primary_key,id_value] - use actual ID value (not placeholder)
            $this->jobModel->setValidationRule('slug', 'required|max_length[255]|is_unique[jobs.slug,id,' . $id . ']');
            log_message('debug', 'Slug changed from "' . $existingSlug . '" to "' . $slug . '", validating uniqueness with ID exclusion: ' . $id);
        } else {
            // Slug hasn't changed, don't include it in update (so validation is skipped)
            log_message('debug', 'Slug unchanged: "' . $slug . '", skipping slug in update data');
        }
        
        // Log the job data being updated for debugging
        log_message('debug', 'Updating job ID: ' . $id);
        log_message('debug', 'Job data keys: ' . implode(', ', array_keys($jobData)));
        log_message('debug', 'Company ID: ' . ($jobData['company_id'] ?? 'NOT SET'));
        log_message('debug', 'Category ID: ' . ($jobData['category_id'] ?? 'NOT SET'));
        log_message('debug', 'Subcategory ID: ' . ($jobData['subcategory_id'] ?? 'NOT SET'));
        log_message('debug', 'Slug: ' . ($jobData['slug'] ?? 'NOT SET'));
        
        // Update the job
        $updateResult = $this->jobModel->update($id, $jobData);
        
        if (!$updateResult) {
            // If update failed, get model errors
            $modelErrors = $this->jobModel->errors();
            $dbError = $this->jobModel->db->error();
            
            // Log the errors for debugging
            log_message('error', 'Job update failed for ID: ' . $id);
            log_message('error', 'Model errors: ' . json_encode($modelErrors));
            if (!empty($dbError)) {
                log_message('error', 'Database error: ' . json_encode($dbError));
            }
            
            if (!empty($modelErrors)) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $modelErrors)
                    ->with('error', 'Validation failed. Please check the errors below.');
            }
            
            // Fallback error with more details
            $errorMsg = 'Failed to update job. Please try again.';
            if (!empty($dbError['message'])) {
                $errorMsg .= ' Error: ' . $dbError['message'];
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMsg);
        }

        return redirect()->to('/manage-jobs')
            ->with('success', 'Job updated successfully!');
    }

    public function jobDetails($slug = null): string
    {
        // If slug is provided, extract ID from it
        // Slug format: company-title-id
        // Also handle if slug contains "public/" prefix (fallback for /public/job/... URLs)
        if ($slug) {
            // Remove "public/" prefix if present
            $slug = preg_replace('#^public/#', '', $slug);
            
            // Extract ID from end of slug (e.g., "google-senior-product-designer-1" -> 1)
            if (preg_match('/-(\d+)$/', $slug, $matches)) {
                $jobId = (int)$matches[1];
                // Pass the ID to the view via query parameter
                $_GET['id'] = $jobId;
            }
        }
        
        return view('home/job-details');
    }

    /**
     * Display collection page with SEO meta tags
     * PUBLIC ACCESS - No authentication required
     * NEVER REDIRECTS - Always shows a page
     */
    public function collectionPage($slug = null)
    {
        // Public access - no authentication check needed
        // Get slug from parameter or URI segment if not provided
        if (empty($slug)) {
            // Try to get from URI segment: /collection/{slug}
            $uri = $this->request->getUri();
            $segments = $uri->getSegments();
            
            // Find 'collection' in segments and get the next segment
            $collectionIndex = array_search('collection', $segments);
            if ($collectionIndex !== false && isset($segments[$collectionIndex + 1])) {
                $slug = $segments[$collectionIndex + 1];
            }
            
            // Alternative: get from path directly using regex
            if (empty($slug)) {
                $path = $uri->getPath();
                // Remove leading/trailing slashes and extract collection slug
                $path = trim($path, '/');
                if (preg_match('#^collection/(.+?)(?:/|$)#', $path, $matches)) {
                    $slug = $matches[1];
                }
            }
            
            // Last resort: get from route parameters
            if (empty($slug)) {
                $routeParams = $this->request->getUri()->getSegments();
                // If route is /collection/{slug}, segment 1 should be the slug
                if (isset($routeParams[1]) && $routeParams[0] === 'collection') {
                    $slug = $routeParams[1];
                }
            }
        }
        
        // Clean the slug - remove any trailing slashes and decode URL encoding
        $slug = $slug ? trim(urldecode($slug), '/') : '';
        
        // If no slug provided, show not found page
        if (empty($slug)) {
            $data = [
                'title' => 'Collection Not Found',
                'message' => 'Please provide a collection slug in the URL.',
                'slug' => ''
            ];
            return view('home/collection-not-found', $data);
        }
        
        // Get collection by slug using direct database query for reliability
        $db = \Config\Database::connect();
        $builder = $db->table('collections');
        
        // Try exact match first
        $collection = $builder->where('slug', $slug)->get()->getRowArray();
        
        // If not found, try case-insensitive search (MySQL default is case-insensitive, but be safe)
        if (!$collection) {
            $builder = $db->table('collections');
            $collection = $builder->where('LOWER(slug)', strtolower($slug))->get()->getRowArray();
        }
        
        // Load image helper
        helper('image');
        
        // Initialize default values
        $jobs = [];
        $isNotFound = false;
        $isInactive = false;
        
        if (!$collection) {
            // Collection doesn't exist - show collection page with not found state
            $isNotFound = true;
            $collection = [
                'name' => 'Collection Not Found',
                'slug' => $slug,
                'site_title' => 'Collection Not Found - TopTopJobs',
                'description' => 'The collection you are looking for does not exist.',
                'meta_description' => 'The collection you are looking for does not exist.',
                'meta_keywords' => '',
            ];
        } elseif ($collection['status'] !== 'active') {
            // Collection exists but is inactive - show collection page with inactive state
            $isInactive = true;
        } else {
            // Collection exists and is active - get jobs
            $jobs = $this->collectionModel->getCollectionJobs($collection['id']);
            
            // Clean job descriptions - remove application information
            foreach ($jobs as &$job) {
                if (!empty($job['description'])) {
                    $job['description'] = $this->cleanJobDescription($job['description']);
                }
            }
            unset($job); // Break reference
        }
        
        // Always prepare meta tags for SEO (even if collection doesn't exist)
        $metaTags = [
            'title' => esc($collection['site_title'] ?? 'Collection - TopTopJobs'),
            'description' => esc($collection['meta_description'] ?? $collection['description'] ?? ''),
            'keywords' => esc($collection['meta_keywords'] ?? ''),
            'og:title' => esc($collection['site_title'] ?? 'Collection - TopTopJobs'),
            'og:description' => esc($collection['meta_description'] ?? $collection['description'] ?? ''),
            'og:type' => 'website',
            'og:url' => current_url(),
            'twitter:card' => 'summary',
            'twitter:title' => esc($collection['site_title'] ?? 'Collection - TopTopJobs'),
            'twitter:description' => esc($collection['meta_description'] ?? $collection['description'] ?? ''),
        ];

        $data = [
            'title' => $collection['site_title'] ?? 'Collection - TopTopJobs',
            'collection' => $collection,
            'jobs' => $jobs,
            'metaTags' => $metaTags,
            'isNotFound' => $isNotFound,
            'isInactive' => $isInactive,
        ];

        // ALWAYS show the collection page template - never redirect
        return view('home/collection', $data);
    }

    /**
     * Clean job description by removing application information sections
     */
    private function cleanJobDescription($description)
    {
        if (empty($description)) {
            return '';
        }
        
        // Remove application information sections
        // Pattern: --- Application Information --- ... (everything after this)
        $description = preg_replace('/---\s*Application\s+Information\s*---.*$/is', '', $description);
        
        // Remove any remaining application-related patterns (case insensitive, multiline)
        $description = preg_replace('/Application\s+Information.*$/is', '', $description);
        $description = preg_replace('/Application\s+URL:.*$/im', '', $description);
        $description = preg_replace('/Apply\s+Online:.*$/im', '', $description);
        $description = preg_replace('/Application\s+Email:.*$/im', '', $description);
        $description = preg_replace('/Application\s+Phone:.*$/im', '', $description);
        $description = preg_replace('/Phone:.*$/im', '', $description);
        
        // Remove URLs that might be application links
        $description = preg_replace('/http[s]?:\/\/[^\s]+/i', '', $description);
        
        // Remove multiple consecutive dashes/lines
        $description = preg_replace('/-{3,}.*$/m', '', $description);
        
        // Clean up extra whitespace and newlines
        $description = preg_replace('/\n{3,}/', "\n\n", $description);
        $description = preg_replace('/\s{3,}/', ' ', $description);
        $description = trim($description);
        
        return $description;
    }

    public function postJob(): string
    {
        // Load geo location helper - use direct require for reliability
        if (!function_exists('getCountryFromIP')) {
            $helperPath = APPPATH . 'Helpers' . DIRECTORY_SEPARATOR . 'geolocation_helper.php';
            if (file_exists($helperPath)) {
                require_once $helperPath;
            } else {
                // Try with forward slash as fallback
                require_once APPPATH . 'Helpers/geolocation_helper.php';
            }
        }
        
        // Get active collections for dropdown (only existing collections created by admin)
        $collections = $this->collectionModel->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
        
        // Get active categories for dropdown
        $categories = $this->categoryModel->getActiveCategories();
        
        // Auto-detect country from IP
        $detectedCountry = getCountryFromIP();
        $countryList = getCountryList();
        
        $data = [
            'collections' => $collections ?? [],
            'categories' => $categories ?? [],
            'detectedCountry' => $detectedCountry,
            'countryList' => $countryList
        ];
        
        return view('home/post-job', $data);
    }

    public function processPostJob()
    {
        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login?redirect=post-job')
                ->with('error', 'Please log in to post a job.');
        }

        // Load geo location helper - use direct require for reliability
        if (!function_exists('getCountryFromIP')) {
            $helperPath = APPPATH . 'Helpers' . DIRECTORY_SEPARATOR . 'geolocation_helper.php';
            if (file_exists($helperPath)) {
                require_once $helperPath;
            } else {
                // Try with forward slash as fallback
                require_once APPPATH . 'Helpers/geolocation_helper.php';
            }
        }

        // Validate and process the job posting
        $validation = \Config\Services::validation();
        
        $jobType = $this->request->getPost('job_type');
        $isRemoteJob = ($jobType === 'remote');
        
        $rules = [
            'job_title' => 'required|max_length[255]',
            'application_email' => 'permit_empty|valid_email',
            'application_url' => 'permit_empty|valid_url',
            'location' => $isRemoteJob ? 'permit_empty|max_length[255]' : 'required|max_length[255]',
            'job_type' => 'required|in_list[full-time,part-time,internship,remote,contract]',
            'application_phone' => 'permit_empty|max_length[20]',
            'salary_min' => 'permit_empty|numeric',
            'salary_max' => 'permit_empty|numeric',
            'category_id' => 'required|integer',
            'subcategory_id' => 'permit_empty|integer',
            'job_category' => 'permit_empty|max_length[100]', // Keep for backward compatibility
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
            'job_image' => 'permit_empty|uploaded[job_image]|max_size[job_image,5120]|ext_in[job_image,png,jpg,jpeg,gif]',
            'collection_id' => 'permit_empty|integer|is_natural',
            'country' => 'permit_empty|max_length[100]',
            'country_code' => 'permit_empty|max_length[2]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Validate salary range (minimum must be less than maximum)
        $salaryMin = $this->request->getPost('salary_min');
        $salaryMax = $this->request->getPost('salary_max');
        if ($salaryMin && $salaryMax && (float)$salaryMin >= (float)$salaryMax) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Minimum salary must be less than maximum salary.');
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
                if (!mkdir($uploadPath, 0755, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $uploadPath);
                }
            }
            
            // Check if directory is writable
            if (!is_writable($uploadPath)) {
                log_message('error', 'Upload directory is not writable: ' . $uploadPath);
            } else {
                // Generate unique filename
                $newName = $file->getRandomName();
                if ($file->move($uploadPath, $newName)) {
                    // Verify file was actually moved
                    $fullPath = $uploadPath . $newName;
                    if (file_exists($fullPath) && is_readable($fullPath)) {
                        // Store relative path in database (e.g., /uploads/company_logos/filename.png)
                        helper('image');
                        $logoPath = upload_path('company_logos/' . $newName);
                        log_message('debug', 'Company logo uploaded successfully: ' . $logoPath);
                    } else {
                        log_message('error', 'File move reported success but file not found: ' . $fullPath);
                    }
                } else {
                    $errors = $file->getErrors();
                    log_message('error', 'Failed to move company logo: ' . implode(', ', $errors));
                }
            }
        }

        // Handle job image upload
        $jobImagePath = null;
        $jobImageFile = $this->request->getFile('job_image');
        
        if ($jobImageFile && $jobImageFile->isValid() && !$jobImageFile->hasMoved()) {
            // Create uploads directory in public folder if it doesn't exist
            $uploadPath = FCPATH . 'uploads/job_images/';
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $uploadPath);
                }
            }
            
            // Check if directory is writable
            if (!is_writable($uploadPath)) {
                log_message('error', 'Upload directory is not writable: ' . $uploadPath);
            } else {
                // Generate unique filename
                $newName = $jobImageFile->getRandomName();
                if ($jobImageFile->move($uploadPath, $newName)) {
                    // Verify file was actually moved
                    $fullPath = $uploadPath . $newName;
                    if (file_exists($fullPath) && is_readable($fullPath)) {
                        // Store relative path in database (e.g., /uploads/job_images/filename.png)
                        helper('image');
                        $jobImagePath = upload_path('job_images/' . $newName);
                        log_message('debug', 'Job image uploaded successfully: ' . $jobImagePath);
                    } else {
                        log_message('error', 'File move reported success but file not found: ' . $fullPath);
                    }
                } else {
                    $errors = $jobImageFile->getErrors();
                    log_message('error', 'Failed to move job image: ' . implode(', ', $errors));
                }
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
            $jobCategory = $this->request->getPost('job_category'); // Keep for backward compatibility
            $categoryId = $this->request->getPost('category_id');
            $subcategoryId = $this->request->getPost('subcategory_id');
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
            
            // Get country from form or auto-detect
            $country = $this->request->getPost('country');
            $countryCode = $this->request->getPost('country_code');
            
            // If country not provided, auto-detect from IP
            if (empty($country) || empty($countryCode)) {
                $detectedCountry = getCountryFromIP();
                $country = $country ?: $detectedCountry['country'];
                $countryCode = $countryCode ?: $detectedCountry['country_code'];
            }
            
            // Default to Sri Lanka if still empty
            if (empty($country) || empty($countryCode)) {
                $country = 'Sri Lanka';
                $countryCode = 'LK';
            }
            
            // Determine if remote from job_type (checkbox removed from form)
            $isRemote = ($jobType === 'remote');
            
            // Set default location for remote jobs if location is empty
            if ($isRemote) {
                $location = trim($location ?? '');
                if (empty($location)) {
                    $location = 'Remote';
                }
            } else {
                // Ensure location is not empty for non-remote jobs
                $location = trim($location ?? '');
                if (empty($location)) {
                    throw new \Exception('Location is required for non-remote jobs');
                }
            }
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

            // isRemote is already determined from job_type above

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
                'category_id' => $categoryId ? (int)$categoryId : null,
                'subcategory_id' => $subcategoryId ? (int)$subcategoryId : null,
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
                'country' => $country,
                'country_code' => strtoupper($countryCode),
                'is_remote' => $isRemote ? 1 : 0,
                'skills_required' => $skillsString ?: $jobCategory, // Use skills if provided, otherwise use category
                'status' => 'active',
                'posted_by' => $userId,
                'posted_at' => date('Y-m-d H:i:s'),
                'expires_at' => $validThrough,
            ];
            
            // Add job image if uploaded
            if ($jobImagePath) {
                $jobData['image'] = $jobImagePath;
            }
            
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

            // Add job to collection if collection_id is provided
            $collectionId = $this->request->getPost('collection_id');
            if ($collectionId && is_numeric($collectionId)) {
                $collectionId = (int) $collectionId;
                // Verify collection exists and is active
                $collection = $this->collectionModel->find($collectionId);
                if ($collection && $collection['status'] === 'active') {
                    // Add job to collection (will not add if already exists)
                    $this->collectionModel->addJobToCollection($collectionId, $jobId);
                }
            }

            // Notify Google Indexing API about the new job (async - don't block on failure)
            try {
                $jobUrl = base_url('job/' . $finalSlug . '/');
                $indexingService = new \App\Services\GoogleIndexingService();
                $indexingService->notifyUrlUpdated($jobUrl);
            } catch (\Exception $e) {
                // Silently fail - don't break job posting if indexing fails
                log_message('debug', 'Google Indexing API notification failed: ' . $e->getMessage());
            }

            return redirect()->to('/jobs')
                ->with('success', 'Job posted successfully!');
                
        } catch (\Exception $e) {
            log_message('error', 'Job posting error: ' . $e->getMessage());
            log_message('error', 'Job posting error trace: ' . $e->getTraceAsString());
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
