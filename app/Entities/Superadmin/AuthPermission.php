<?php

namespace App\Entities\Superadmin;

use App\Entities\Cast\Slug;
use CodeIgniter\Entity\Entity;

class AuthPermission extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function setName(String $name)
    {
        $this->attributes['name'] = strtolower($name);
        return $this;
    }
}
