<?php

namespace App\Controllers;

use App\Models\CollectionModel;
use App\Models\JobModel;
use App\Models\CompanyModel;
use App\Models\SiteSettingsModel;

class AdminController extends BaseController
{
    protected $helpers = ['url', 'form', 'image'];
    protected $session;
    protected $collectionModel;
    protected $jobModel;
    protected $companyModel;
    protected $siteSettingsModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->collectionModel = new CollectionModel();
        $this->jobModel = new JobModel();
        $this->companyModel = new CompanyModel();
        $this->siteSettingsModel = new SiteSettingsModel();
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

    /**
     * List all collections
     */
    public function collections(): string
    {
        $collections = $this->collectionModel->getAllWithJobCount();
        
        $data = [
            'title' => 'Manage Collections',
            'user' => [
                'name' => $this->session->get('first_name') . ' ' . $this->session->get('last_name'),
                'email' => $this->session->get('email'),
                'user_type' => $this->session->get('user_type'),
            ],
            'collections' => $collections
        ];

        return view('admin/collections/index', $data);
    }

    /**
     * Show create collection form
     */
    public function createCollection(): string
    {
        $data = [
            'title' => 'Create Collection',
            'user' => [
                'name' => $this->session->get('first_name') . ' ' . $this->session->get('last_name'),
                'email' => $this->session->get('email'),
                'user_type' => $this->session->get('user_type'),
            ],
            'collection' => null
        ];

        return view('admin/collections/form', $data);
    }

    /**
     * Store new collection
     */
    public function storeCollection()
    {
        // CSRF protection is handled by CodeIgniter automatically
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|max_length[255]',
            'site_title' => 'required|max_length[255]',
            'meta_description' => 'permit_empty|max_length[500]',
            'meta_keywords' => 'permit_empty|max_length[500]',
            'description' => 'permit_empty',
            'status' => 'required|in_list[active,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $name = esc($this->request->getPost('name'));
        $slug = $this->collectionModel->generateSlug($name);
        
        $data = [
            'name' => esc($this->request->getPost('name')),
            'slug' => $slug,
            'site_title' => esc($this->request->getPost('site_title')),
            'meta_description' => esc($this->request->getPost('meta_description')),
            'meta_keywords' => esc($this->request->getPost('meta_keywords')),
            'description' => esc($this->request->getPost('description')),
            'status' => esc($this->request->getPost('status')),
            'created_by' => $this->session->get('user_id'),
        ];

        if ($this->collectionModel->insert($data)) {
            return redirect()->to('/admin/collections')
                ->with('success', 'Collection created successfully!');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create collection. Please try again.');
    }

    /**
     * Show edit collection form
     */
    public function editCollection($id = null): string
    {
        // Get ID from parameter or URI segment if not provided
        if ($id === null) {
            $id = $this->request->getUri()->getSegment(3); // /admin/collections/{id}/edit
        }
        
        if (!$id || !is_numeric($id)) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Invalid collection ID.');
        }
        
        $id = (int) $id;
        $collection = $this->collectionModel->find($id);
        
        if (!$collection) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Collection not found.');
        }

        $data = [
            'title' => 'Edit Collection',
            'user' => [
                'name' => $this->session->get('first_name') . ' ' . $this->session->get('last_name'),
                'email' => $this->session->get('email'),
                'user_type' => $this->session->get('user_type'),
            ],
            'collection' => $collection
        ];

        return view('admin/collections/form', $data);
    }

    /**
     * Update collection
     */
    public function updateCollection($id = null)
    {
        // Get ID from parameter or URI segment if not provided
        if ($id === null) {
            $id = $this->request->getUri()->getSegment(3); // /admin/collections/{id}/update
        }
        
        if (!$id || !is_numeric($id)) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Invalid collection ID.');
        }
        
        $id = (int) $id;
        
        // CSRF protection is handled by CodeIgniter automatically
        $collection = $this->collectionModel->find($id);
        
        if (!$collection) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Collection not found.');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|max_length[255]',
            'site_title' => 'required|max_length[255]',
            'meta_description' => 'permit_empty|max_length[500]',
            'meta_keywords' => 'permit_empty|max_length[500]',
            'description' => 'permit_empty',
            'status' => 'required|in_list[active,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $name = esc($this->request->getPost('name'));
        $slug = $this->collectionModel->generateSlug($name);
        
        // If name changed, regenerate slug
        if ($collection['name'] !== $name) {
            $slug = $this->collectionModel->generateSlug($name);
        } else {
            $slug = $collection['slug'];
        }
        
        $data = [
            'name' => esc($this->request->getPost('name')),
            'slug' => $slug,
            'site_title' => esc($this->request->getPost('site_title')),
            'meta_description' => esc($this->request->getPost('meta_description')),
            'meta_keywords' => esc($this->request->getPost('meta_keywords')),
            'description' => esc($this->request->getPost('description')),
            'status' => esc($this->request->getPost('status')),
        ];

        if ($this->collectionModel->update($id, $data)) {
            return redirect()->to('/admin/collections')
                ->with('success', 'Collection updated successfully!');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update collection. Please try again.');
    }

    /**
     * Delete collection
     */
    public function deleteCollection($id = null)
    {
        // Get ID from parameter or URI segment if not provided
        if ($id === null) {
            $id = $this->request->getUri()->getSegment(3); // /admin/collections/{id}/delete
        }
        
        if (!$id || !is_numeric($id)) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Invalid collection ID.');
        }
        
        $id = (int) $id;
        $collection = $this->collectionModel->find($id);
        
        if (!$collection) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Collection not found.');
        }

        if ($this->collectionModel->delete($id)) {
            return redirect()->to('/admin/collections')
                ->with('success', 'Collection deleted successfully!');
        }

        return redirect()->to('/admin/collections')
            ->with('error', 'Failed to delete collection. Please try again.');
    }

    /**
     * Manage jobs in a collection
     */
    public function manageCollectionJobs($id = null): string
    {
        // Get ID from parameter or URI segment if not provided
        if ($id === null) {
            $id = $this->request->getUri()->getSegment(3); // /admin/collections/{id}/jobs
        }
        
        if (!$id || !is_numeric($id)) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Invalid collection ID.');
        }
        
        $id = (int) $id;
        $collection = $this->collectionModel->find($id);
        
        if (!$collection) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Collection not found.');
        }

        // Get jobs in collection
        $collectionJobs = $this->collectionModel->getCollectionJobs($id);
        
        // Get all active jobs for selection
        $allJobs = $this->jobModel->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        // Add company info to all jobs
        foreach ($allJobs as &$job) {
            $company = $this->companyModel->find($job['company_id']);
            $job['company_name'] = $company['name'] ?? 'Unknown Company';
        }

        $data = [
            'title' => 'Manage Collection Jobs',
            'user' => [
                'name' => $this->session->get('first_name') . ' ' . $this->session->get('last_name'),
                'email' => $this->session->get('email'),
                'user_type' => $this->session->get('user_type'),
            ],
            'collection' => $collection,
            'collectionJobs' => $collectionJobs,
            'allJobs' => $allJobs
        ];

        return view('admin/collections/manage-jobs', $data);
    }

    /**
     * Add job to collection
     */
    public function addJobToCollection($collectionId = null)
    {
        // Get collection ID from parameter or URI segment if not provided
        if ($collectionId === null) {
            $collectionId = $this->request->getUri()->getSegment(3); // /admin/collections/{id}/jobs/add
        }
        
        if (!$collectionId || !is_numeric($collectionId)) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Invalid collection ID.');
        }
        
        $collectionId = (int) $collectionId;
        
        // CSRF protection is handled by CodeIgniter automatically
        $jobId = (int) $this->request->getPost('job_id');
        
        if (!$jobId) {
            return redirect()->back()
                ->with('error', 'Please select a job.');
        }

        $collection = $this->collectionModel->find($collectionId);
        if (!$collection) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Collection not found.');
        }

        if ($this->collectionModel->addJobToCollection($collectionId, $jobId)) {
            return redirect()->back()
                ->with('success', 'Job added to collection successfully!');
        }

        return redirect()->back()
            ->with('error', 'Job is already in this collection or failed to add.');
    }

    /**
     * Remove job from collection
     */
    public function removeJobFromCollection($collectionId = null, $jobId = null)
    {
        // Get collection ID from parameter or URI segment if not provided
        if ($collectionId === null) {
            $collectionId = $this->request->getUri()->getSegment(3); // /admin/collections/{id}/jobs/{jobId}/remove
        }
        
        // Get job ID from parameter or URI segment if not provided
        if ($jobId === null) {
            $jobId = $this->request->getUri()->getSegment(5); // /admin/collections/{id}/jobs/{jobId}/remove
        }
        
        if (!$collectionId || !is_numeric($collectionId)) {
            return redirect()->to('/admin/collections')
                ->with('error', 'Invalid collection ID.');
        }
        
        if (!$jobId || !is_numeric($jobId)) {
            return redirect()->back()
                ->with('error', 'Invalid job ID.');
        }
        
        $collectionId = (int) $collectionId;
        $jobId = (int) $jobId;
        
        if ($this->collectionModel->removeJobFromCollection($collectionId, $jobId)) {
            return redirect()->back()
                ->with('success', 'Job removed from collection successfully!');
        }

        return redirect()->back()
            ->with('error', 'Failed to remove job from collection.');
    }

    /**
     * Site Settings Page
     */
    public function settings(): string
    {
        $siteSettings = $this->siteSettingsModel->getAllSettings();
        
        $data = [
            'title' => 'Site Settings',
            'user' => [
                'name' => $this->session->get('first_name') . ' ' . $this->session->get('last_name'),
                'email' => $this->session->get('email'),
                'user_type' => $this->session->get('user_type'),
            ],
            'settings' => $siteSettings
        ];

        return view('admin/settings', $data);
    }

    /**
     * Update Site Settings
     */
    public function updateSettings()
    {
        // Handle favicon upload
        $faviconFile = $this->request->getFile('favicon');
        if ($faviconFile && $faviconFile->isValid() && !$faviconFile->hasMoved()) {
            // Validate file type
            $allowedTypes = ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/jpeg', 'image/svg+xml'];
            if (!in_array($faviconFile->getMimeType(), $allowedTypes)) {
                return redirect()->back()
                    ->with('error', 'Invalid file type. Please upload a PNG, ICO, JPEG, or SVG file.');
            }

            // Validate file size (max 2MB)
            if ($faviconFile->getSize() > 2097152) {
                return redirect()->back()
                    ->with('error', 'File size too large. Maximum size is 2MB.');
            }

            // Create uploads directory if it doesn't exist
            $uploadPath = WRITEPATH . '../public/uploads/favicons/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old favicon if exists
            $oldFavicon = $this->siteSettingsModel->getSetting('site_favicon');
            if ($oldFavicon && file_exists(WRITEPATH . '../public/' . $oldFavicon)) {
                @unlink(WRITEPATH . '../public/' . $oldFavicon);
            }

            // Generate unique filename
            $newName = 'favicon_' . time() . '.' . $faviconFile->getExtension();
            $faviconFile->move($uploadPath, $newName);

            // Save favicon path (relative to public directory)
            $faviconPath = 'uploads/favicons/' . $newName;
            $this->siteSettingsModel->setSetting('site_favicon', $faviconPath, 'file');
        }

        // Update other settings
        $siteName = $this->request->getPost('site_name');
        if ($siteName) {
            $this->siteSettingsModel->setSetting('site_name', $siteName, 'text');
        }

        $siteDescription = $this->request->getPost('site_description');
        if ($siteDescription !== null) {
            $this->siteSettingsModel->setSetting('site_description', $siteDescription, 'text');
        }

        // Page meta settings
        $homeTitle = $this->request->getPost('home_title');
        $homeDescription = $this->request->getPost('home_description');
        $jobsTitle = $this->request->getPost('jobs_title');
        $jobsDescription = $this->request->getPost('jobs_description');
        $postJobTitle = $this->request->getPost('postjob_title');
        $postJobDescription = $this->request->getPost('postjob_description');

        if ($homeTitle !== null) {
            $this->siteSettingsModel->setSetting('home_title', $homeTitle, 'text');
        }
        if ($homeDescription !== null) {
            $this->siteSettingsModel->setSetting('home_description', $homeDescription, 'text');
        }
        if ($jobsTitle !== null) {
            $this->siteSettingsModel->setSetting('jobs_title', $jobsTitle, 'text');
        }
        if ($jobsDescription !== null) {
            $this->siteSettingsModel->setSetting('jobs_description', $jobsDescription, 'text');
        }
        if ($postJobTitle !== null) {
            $this->siteSettingsModel->setSetting('postjob_title', $postJobTitle, 'text');
        }
        if ($postJobDescription !== null) {
            $this->siteSettingsModel->setSetting('postjob_description', $postJobDescription, 'text');
        }

        return redirect()->back()
            ->with('success', 'Settings updated successfully!');
    }
}

