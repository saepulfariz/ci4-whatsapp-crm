<?php

namespace App\Models;

use App\Entities\Product;
use App\Traits\LogUserTrait;
use CodeIgniter\Model;

class ProductModel extends Model
{
    use LogUserTrait;

    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Product::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'name',
        'price',
        'qty',
        'image',
        'description',
        'is_active',
    ];
    protected $cacheKey = 'products';

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

    public function getAllProductQty()
    {
        return $this->select('products.*, categories.name as category_name')
            ->select('(SELECT COALESCE(SUM(td.qty), 0) FROM transaction_details td JOIN transactions t ON t.id = td.transaction_id WHERE td.product_id = products.id AND t.status NOT IN ("delivered", "cancelled") AND t.deleted_at IS NULL) as hold_qty')
            ->select('(products.qty - (SELECT COALESCE(SUM(td.qty), 0) FROM transaction_details td JOIN transactions t ON t.id = td.transaction_id WHERE td.product_id = products.id AND t.status NOT IN ("delivered", "cancelled") AND t.deleted_at IS NULL)) as stock')
            ->join('categories', 'categories.id = products.category_id')
            ->orderBy('products.id', 'desc')
            ->findAll();
    }
}
