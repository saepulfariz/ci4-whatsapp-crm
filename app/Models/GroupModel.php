<?php

namespace App\Models;

use App\Entities\Group;
use App\Traits\LogUserTrait;
use CodeIgniter\Model;

class GroupModel extends Model
{
    use LogUserTrait;

    protected $table            = 'groups';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Group::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'name',
        'description',
        'status',
    ];
    protected $cacheKey = 'groups';

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = false;

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
