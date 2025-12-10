<?php

namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table            = 'jobs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields   = true;
    protected $allowedFields    = [
        'company_id',
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'job_type',
        'experience_level',
        'min_experience',
        'max_experience',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_period',
        'is_salary_disclosed',
        'location',
        'application_email',
        'application_url',
        'application_phone',
        'latitude',
        'longitude',
        'is_remote',
        'skills_required',
        'image',
        'status',
        'views_count',
        'applications_count',
        'featured',
        'urgent',
        'posted_by',
        'posted_at',
        'expires_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'company_id' => 'required|integer',
        'title' => 'required|max_length[255]',
        'slug' => 'required|max_length[255]|is_unique[jobs.slug,id,{id}]',
        'description' => 'permit_empty',
        'location' => 'required|max_length[255]',
        'job_type' => 'required|in_list[full-time,part-time,contract,internship,remote,freelance]',
    ];

    protected $validationMessages = [];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Generate slug from company name, job title, and ID
     */
    public function generateSlug($companyName, $jobTitle, $jobId = null)
    {
        $companySlug = strtolower($companyName);
        $companySlug = preg_replace('/[^a-z0-9]+/', '-', $companySlug);
        $companySlug = trim($companySlug, '-');
        
        $titleSlug = strtolower($jobTitle);
        $titleSlug = preg_replace('/[^a-z0-9]+/', '-', $titleSlug);
        $titleSlug = trim($titleSlug, '-');
        
        $slug = $companySlug . '-' . $titleSlug;
        
        if ($jobId) {
            // Add ID to slug
            $slug .= '-' . $jobId;
        } else {
            // Generate temporary unique slug (will be updated with ID later)
            $originalSlug = $slug;
            $counter = 1;
            while ($this->where('slug', $slug)->first()) {
                $slug = $originalSlug . '-temp-' . $counter;
                $counter++;
            }
        }
        
        return $slug;
    }

    /**
     * Map experience years to experience level
     */
    public function mapExperienceLevel($years)
    {
        if (empty($years) || $years == 0) {
            return 'fresher';
        } elseif ($years <= 2) {
            return 'junior';
        } elseif ($years <= 5) {
            return 'mid';
        } elseif ($years <= 8) {
            return 'senior';
        } else {
            return 'lead';
        }
    }
}

