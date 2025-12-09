<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $table            = 'companies';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields   = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'logo',
        'website',
        'description',
        'industry',
        'size',
        'founded_year',
        'headquarters',
        'rating',
        'total_reviews'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'slug' => 'required|max_length[255]|is_unique[companies.slug,id,{id}]',
    ];

    protected $validationMessages = [];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Generate slug from company name
     */
    public function generateSlug($name)
    {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while ($this->where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Find or create company
     */
    public function findOrCreate($name, $data = [])
    {
        // Try to find existing company by name
        $company = $this->where('name', $name)->first();
        
        if ($company) {
            // Update if new data provided
            if (!empty($data)) {
                $this->update($company['id'], $data);
                return $this->find($company['id']);
            }
            return $company;
        }
        
        // Create new company
        $companyData = [
            'name' => $name,
            'slug' => $this->generateSlug($name),
            'logo' => $data['logo'] ?? null,
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'industry' => $data['industry'] ?? null,
        ];
        
        $companyId = $this->insert($companyData);
        return $this->find($companyId);
    }
}

