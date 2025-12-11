<?php

namespace App\Models;

use CodeIgniter\Model;

class CollectionModel extends Model
{
    protected $table            = 'collections';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields   = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'site_title',
        'meta_description',
        'meta_keywords',
        'description',
        'status',
        'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'slug' => 'required|max_length[255]|is_unique[collections.slug,id,{id}]',
        'site_title' => 'required|max_length[255]',
        'meta_description' => 'permit_empty|max_length[500]',
        'meta_keywords' => 'permit_empty|max_length[500]',
        'description' => 'permit_empty',
        'status' => 'required|in_list[active,inactive]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Collection name is required',
            'max_length' => 'Collection name cannot exceed 255 characters',
        ],
        'slug' => [
            'required' => 'Slug is required',
            'max_length' => 'Slug cannot exceed 255 characters',
            'is_unique' => 'This slug is already in use',
        ],
        'site_title' => [
            'required' => 'Site title is required',
            'max_length' => 'Site title cannot exceed 255 characters',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Generate slug from collection name
     * @param string $name Collection name
     * @param int|null $excludeId ID to exclude when checking uniqueness (for updates)
     */
    public function generateSlug($name, $excludeId = null)
    {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure uniqueness (excluding current ID if updating)
        $originalSlug = $slug;
        $counter = 1;
        
        // Build query
        $query = $this->where('slug', $slug);
        
        // Exclude current ID if updating
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }
        
        while ($query->first()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            $query = $this->where('slug', $slug);
            if ($excludeId !== null) {
                $query->where('id !=', $excludeId);
            }
        }
        
        return $slug;
    }

    /**
     * Get collection with jobs
     */
    public function getCollectionWithJobs($id)
    {
        $collection = $this->find($id);
        if (!$collection) {
            return null;
        }

        $db = \Config\Database::connect();
        $builder = $db->table('collection_jobs');
        $builder->select('jobs.*, companies.name as company_name, companies.slug as company_slug, companies.logo as company_logo');
        $builder->join('jobs', 'jobs.id = collection_jobs.job_id');
        $builder->join('companies', 'companies.id = jobs.company_id');
        $builder->where('collection_jobs.collection_id', $id);
        $builder->orderBy('collection_jobs.sort_order', 'ASC');
        $builder->orderBy('collection_jobs.created_at', 'DESC');
        
        $collection['jobs'] = $builder->get()->getResultArray();
        
        return $collection;
    }

    /**
     * Get collection by slug
     */
    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)
                    ->where('status', 'active')
                    ->first();
    }

    /**
     * Add job to collection
     */
    public function addJobToCollection($collectionId, $jobId, $sortOrder = 0)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('collection_jobs');
        
        // Check if already exists
        $exists = $builder->where('collection_id', $collectionId)
                          ->where('job_id', $jobId)
                          ->get()
                          ->getRow();
        
        if ($exists) {
            return false; // Already exists
        }
        
        $data = [
            'collection_id' => $collectionId,
            'job_id' => $jobId,
            'sort_order' => $sortOrder,
        ];
        
        return $builder->insert($data);
    }

    /**
     * Remove job from collection
     */
    public function removeJobFromCollection($collectionId, $jobId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('collection_jobs');
        
        return $builder->where('collection_id', $collectionId)
                      ->where('job_id', $jobId)
                      ->delete();
    }

    /**
     * Get all jobs in a collection
     */
    public function getCollectionJobs($collectionId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('collection_jobs');
        $builder->select('jobs.*, companies.name as company_name, companies.slug as company_slug, companies.logo as company_logo, collection_jobs.sort_order, categories.name as category_name, subcategories.name as subcategory_name');
        $builder->join('jobs', 'jobs.id = collection_jobs.job_id');
        $builder->join('companies', 'companies.id = jobs.company_id');
        $builder->join('categories', 'categories.id = jobs.category_id', 'left');
        $builder->join('subcategories', 'subcategories.id = jobs.subcategory_id', 'left');
        $builder->where('collection_jobs.collection_id', $collectionId);
        $builder->where('jobs.status', 'active');
        $builder->orderBy('collection_jobs.sort_order', 'ASC');
        $builder->orderBy('collection_jobs.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Update job order in collection
     */
    public function updateJobOrder($collectionId, $jobId, $sortOrder)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('collection_jobs');
        
        return $builder->where('collection_id', $collectionId)
                      ->where('job_id', $jobId)
                      ->update(['sort_order' => $sortOrder]);
    }

    /**
     * Get all collections with job count
     */
    public function getAllWithJobCount()
    {
        $collections = $this->findAll();
        
        $db = \Config\Database::connect();
        foreach ($collections as &$collection) {
            $builder = $db->table('collection_jobs');
            $count = $builder->where('collection_id', $collection['id'])->countAllResults();
            $collection['job_count'] = $count;
        }
        
        return $collections;
    }
}

