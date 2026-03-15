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
        'code',
        'name',
        'price',
        'qty',
        'min_qty',
        'cogs',
        'image',
        'description',
        'status',
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

    public function getAllProductQty($category_id = null)
    {
        $data = $this->select('products.*, categories.name as category_name')
            ->select('(SELECT COALESCE(SUM(td.qty), 0) FROM transaction_details td JOIN transactions t ON t.id = td.transaction_id WHERE td.product_id = products.id AND t.status NOT IN ("completed", "cancelled") AND t.deleted_at IS NULL) as hold_qty')
            ->select('(products.qty - (SELECT COALESCE(SUM(td.qty), 0) FROM transaction_details td JOIN transactions t ON t.id = td.transaction_id WHERE td.product_id = products.id AND t.status NOT IN ("completed", "cancelled") AND t.deleted_at IS NULL)) as stock')
            ->join('categories', 'categories.id = products.category_id')
            ->orderBy('products.id', 'desc');

        if ($category_id) {
            return $data->where('category_id', $category_id)->findAll();
        } else {

            return $data->findAll();
        }
    }

    public function getProductsWithEffectiveStock($excludeTransactionId = null)
    {
        $products = $this->findAll();
        $db = \Config\Database::connect();
        $builder = $db->table('transaction_details td');
        $builder->select('td.product_id, SUM(td.qty) as hold_qty');
        $builder->join('transactions t', 't.id = td.transaction_id');
        $builder->whereNotIn('t.status', ['completed', 'cancelled']);

        if ($excludeTransactionId) {
            $builder->where('t.id !=', $excludeTransactionId);
        }

        $builder->where('t.deleted_at IS NULL');
        $builder->groupBy('td.product_id');
        $holds = $builder->get()->getResult();

        $holdMap = [];
        foreach ($holds as $h) {
            $holdMap[$h->product_id] = $h->hold_qty;
        }

        $restockMap = [];
        if ($excludeTransactionId) {
            $trans = $this->model->find($excludeTransactionId);
            if ($trans && $trans->status === 'completed') {
                $oldDetails = $this->detailModel->where('transaction_id', $excludeTransactionId)->findAll();
                foreach ($oldDetails as $od) {
                    if (!isset($restockMap[$od->product_id])) $restockMap[$od->product_id] = 0;
                    $restockMap[$od->product_id] += $od->qty;
                }
            }
        }

        foreach ($products as $p) {
            $hold = isset($holdMap[$p->id]) ? $holdMap[$p->id] : 0;
            $restock = isset($restockMap[$p->id]) ? $restockMap[$p->id] : 0;
            $p->qty = max(0, ($p->qty + $restock) - $hold);
        }

        return $products;
    }

    public function getAllProductProfit($product_id = null, $category_id = null)
    {
        $data = $this->select('products.*, categories.name as category_name')

            ->select('(SELECT COALESCE(SUM(td.qty),0)
                FROM transaction_details td
                JOIN transactions t ON t.id = td.transaction_id
                WHERE td.product_id = products.id
                AND t.deleted_at IS NULL
                AND t.status != "cancelled") as count_transaction')
            ->join('categories', 'categories.id = products.category_id')
            ->orderBy('products.id', 'desc');

        if ($product_id) {
            $data->where('products.id', $product_id);
        }

        if ($category_id) {
            $data->where('products.category_id', $category_id);
        }

        return $data->findAll();
    }

    public function getAllProductProfitTransaction($product_id = null, $category_id = null, $start_date = null, $end_date = null)
    {
        // products by transaction_details
        $data = db_connect()->table('transaction_details td')
            ->select('sum(td.qty) as count_transaction, p.name as name, p.cogs as cogs, p.price as price, c.name as category_name')
            ->join('products p', 'p.id = td.product_id')
            ->join('categories c', 'c.id = p.category_id')
            ->join('transactions t', 't.id = td.transaction_id')
            ->groupBy('td.product_id')
            ->groupBy('p.category_id')
            ->groupBy('p.cogs')
            ->groupBy('p.price')
            ->where('t.deleted_at IS NULL')
            ->where('t.status != "cancelled"');

        if ($start_date && $end_date) {
            $data->where('t.created_at >=', $start_date)->where('t.created_at <=', $end_date);
        }

        if ($product_id) {
            $data->where('td.product_id', $product_id);
        }

        if ($category_id) {
            $data->where('p.category_id', $category_id);
        }

        return $data->get()->getResult();
    }
}
