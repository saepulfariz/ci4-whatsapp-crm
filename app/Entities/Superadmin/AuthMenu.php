<?php

namespace App\Entities\Superadmin;

use CodeIgniter\Entity\Entity;

class AuthMenu extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}
