<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class JobCategory extends Entity
{
    protected $attributes = [
        'id' => null,
        'name' => null,
        'slug' => null,
        'is_active' => 1,
        'created_at' => null,
        'updated_at' => null
    ];

    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'is_active' => 'boolean',
        'id' => 'integer'
    ];

    public function activate()
    {
        $this->attributes['is_active'] = 1;
        return $this;
    }

    public function deactivate()
    {
        $this->attributes['is_active'] = 0;
        return $this;
    }

    public function setSlug(string $slug = null)
    {
        if ($slug === null) {
            $this->attributes['slug'] = url_title($this->attributes['name'], '-', true);
        } else {
            $this->attributes['slug'] = $slug;
        }
        return $this;
    }
}
