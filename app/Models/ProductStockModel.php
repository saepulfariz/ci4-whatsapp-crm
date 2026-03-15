<?php

namespace App\Models;

use App\Entities\ProductStock;
use App\Traits\LogUserTrait;
use CodeIgniter\Model;

class ProductStockModel extends Model
{
    use LogUserTrait;

    protected $table            = 'product_stocks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = ProductStock::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'type',
        'qty',
        'current_stock',
        'prev_stock',
        'note',
        'date',
    ];
    protected $cacheKey = 'product_stocks';

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

    public $types = [
        [
            'id' => 'Stock In',
            'name' => 'Stock In',
            'operation' => '+',
        ],
        // [
        //     'id' => 'Stock Out',
        //     'name' => 'Stock Out',
        //     'operation' => '-',
        // ],
        [
            'id' => 'Adjustment Increase',
            'name' => 'Adjustment Increase',
            'operation' => '+',
        ],
        [
            'id' => 'Adjustment Decrease',
            'name' => 'Adjustment Decrease',
            'operation' => '-',
        ],
    ];
}
