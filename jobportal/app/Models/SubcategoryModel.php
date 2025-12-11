<?php

namespace App\Models;

use CodeIgniter\Model;

class SubcategoryModel extends Model
{
    protected $table            = 'subcategories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'name',
        'slug',
        'description',
        'status',
        'sort_order'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'category_id' => 'required|integer',
        'name' => 'required|max_length[255]',
        'slug' => 'required|max_length[255]|is_unique[subcategories.slug,id,{id}]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    /**
     * Get subcategories by category ID
     */
    public function getSubcategoriesByCategory($categoryId)
    {
        return $this->where('category_id', $categoryId)
                    ->where('status', 'active')
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get all active subcategories
     */
    public function getActiveSubcategories()
    {
        return $this->where('status', 'active')
                    ->orderBy('category_id', 'ASC')
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get subcategory with category information
     */
    public function getSubcategoryWithCategory($subcategoryId)
    {
        $subcategory = $this->find($subcategoryId);
        if (!$subcategory) {
            return null;
        }

        $categoryModel = new CategoryModel();
        $subcategory['category'] = $categoryModel->find($subcategory['category_id']);

        return $subcategory;
    }

    /**
     * Generate slug from name
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
}

