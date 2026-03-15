<?php

namespace App\Models;

use App\Entities\Category;
use App\Traits\LogUserTrait;
use CodeIgniter\Model;

class CategoryModel extends Model
{
    use LogUserTrait;

    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Category::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'name',
        'description',
        'status',
    ];
    protected $cacheKey = 'categories';

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
