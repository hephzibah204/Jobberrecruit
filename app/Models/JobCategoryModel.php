<?php

namespace App\Models;

use CodeIgniter\Model;

class JobCategoryModel extends Model
{
    protected $table      = 'job_categories';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\JobCategory::class;
    protected $allowedFields = ['name', 'slug', 'is_active'];

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]|is_unique[job_categories.name,id,{id}]',
        'slug' => 'required|min_length[2]|max_length[100]|is_unique[job_categories.slug,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required',
            'min_length' => 'Category name must be at least 2 characters',
            'max_length' => 'Category name cannot exceed 100 characters',
            'is_unique' => 'This category name already exists'
        ],
        'slug' => [
            'required' => 'Category slug is required',
            'min_length' => 'Category slug must be at least 2 characters',
            'max_length' => 'Category slug cannot exceed 100 characters',
            'is_unique' => 'This category slug already exists'
        ]
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getCategoriesWithStats()
    {
        return $this->db->table('job_categories c')
            ->select('c.*, 
                (SELECT COUNT(*) FROM jobs WHERE category_id = c.id) as job_count')
            ->orderBy('c.name', 'ASC')
            ->get()
            ->getResult();
    }

    public function getActiveCategories()
    {
        return $this->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function generateSlug($name)
    {
        $slug = url_title($name, '-', true);
        $count = $this->where('slug', $slug)->countAllResults();

        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        return $slug;
    }
}
