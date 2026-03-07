<?php

namespace App\Models\Superadmin;

use App\Entities\Superadmin\AuthMenu;
use App\Traits\LogUserTrait;
use CodeIgniter\Model;

class AuthMenuModel extends Model
{
    use LogUserTrait;

    protected $table            = 'auth_menus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType       = AuthMenu::class;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'parent_id',
        'title',
        'icon',
        'route',
        'order',
        'permission',
        'active'
    ];
    protected $cacheKey = 'auth_menus';

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['beforeInsert'];
    protected $afterInsert    = ['afterInsert'];
    protected $beforeUpdate   = ['beforeUpdate'];
    protected $afterUpdate    = ['afterUpdate'];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['beforeDelete'];
    protected $afterDelete    = ['afterDelete'];

    // public $logName = false;
    public $logId = true;
}
