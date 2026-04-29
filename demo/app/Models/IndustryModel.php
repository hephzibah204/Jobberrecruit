<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Industry;

class IndustryModel extends Model
{
    protected $table      = 'industries';
    protected $primaryKey = 'id';
    protected $returnType = Industry::class;
    protected $allowedFields = [
        'parent_id',
        'name',
        'slug',
        'is_active'
    ];

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]|is_unique[industries.name,id,{id}]',
        'slug' => 'required|min_length[2]|max_length[100]|is_unique[industries.slug,id,{id}]',
        'parent_id' => 'permit_empty|numeric'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Industry name is required',
            'min_length' => 'Industry name must be at least 2 characters',
            'max_length' => 'Industry name cannot exceed 100 characters',
            'is_unique' => 'This industry name already exists'
        ],
        'slug' => [
            'required' => 'Industry slug is required',
            'min_length' => 'Industry slug must be at least 2 characters',
            'max_length' => 'Industry slug cannot exceed 100 characters',
            'is_unique' => 'This industry slug already exists'
        ],
        'parent_id' => [
            'numeric' => 'Invalid parent industry selection'
        ]
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Find root industries (parent_id IS NULL)
     */
    public function findRoots()
    {
        return $this->where('parent_id', null)->orderBy('name')->findAll();
    }

    /**
     * Recursively build a tree of industries (simple implementation)
     */
    public function buildTree($parentId = null)
    {
        $items = $this->where('parent_id', $parentId)->findAll();
        $tree = [];

        foreach ($items as $item) {
            $node = $item;
            $node->children = $this->buildTree($item->id);
            $tree[] = $node;
        }

        return $tree;
    }

    public function getIndustriesWithStats()
    {
        return $this->db->table('industries i')
            ->select('i.*, 
                p.name as parent_name,
                (SELECT COUNT(*) FROM employer_industries WHERE industry_id = i.id) as employer_count,
                (SELECT COUNT(*) FROM job_seeker_industries WHERE industry_id = i.id) as job_seeker_count,
                (SELECT COUNT(*) FROM industries WHERE parent_id = i.id) as child_count')
            ->join('industries p', 'p.id = i.parent_id', 'left')
            ->orderBy('COALESCE(p.name, i.name)')
            ->orderBy('i.name', 'ASC')
            ->get()
            ->getResult();
    }

    public function getActiveIndustries()
    {
        return $this->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function getParentIndustries($excludeId = null)
    {
        $builder = $this->where('parent_id', null)
            ->where('is_active', 1)
            ->orderBy('name', 'ASC');

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->findAll();
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

    public function getHierarchy()
    {
        $industries = $this->where('is_active', 1)
            ->orderBy('COALESCE(parent_id, 0)', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $hierarchy = [];
        foreach ($industries as $industry) {
            if ($industry->parent_id === null) {
                $hierarchy[$industry->id] = [
                    'industry' => $industry,
                    'children' => []
                ];
            } else {
                if (isset($hierarchy[$industry->parent_id])) {
                    $hierarchy[$industry->parent_id]['children'][] = $industry;
                }
            }
        }

        return $hierarchy;
    }
}
