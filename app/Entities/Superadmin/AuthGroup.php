<?php

namespace App\Entities\Superadmin;

use App\Entities\Cast\Slug;
use CodeIgniter\Entity\Entity;

class AuthGroup extends Entity
{
    protected $datamap = [
        'nama' => 'name',
        'judul' => 'title',
        'deskripsi' => 'decription',
    ];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'name' => 'slug',
        // 'name' => 'slug[slug]',
        // 'name' => 'slug[slugify]',
        // 'name' => 'slug[url_slug, params2]',
    ];

    // Bind the type to the handler
    protected $castHandlers = [
        'slug' => Slug::class,
    ];


    public function setName(String $name)
    {
        $this->attributes['name'] = strtolower($name);
        return $this;
    }

    public function getTitle()
    {
        return ucwords($this->attributes['title']);
    }
}
