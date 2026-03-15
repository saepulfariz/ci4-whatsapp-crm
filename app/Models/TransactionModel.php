<?php

namespace App\Models;

use App\Entities\Transaction;
use App\Traits\LogUserTrait;
use CodeIgniter\Model;

class TransactionModel extends Model
{
    use LogUserTrait;

    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Transaction::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'customer_id',
        'status',
        'order_date',
        'schedule_date',
        'delivery_date',
        'subtotal_price',
        'discount_total',
        'tax_total',
        'total_amount', // grand_total
        'paid_amount',
        'refund_amount',
        'payment_status',
        'note',
    ];
    protected $cacheKey = 'transactions';

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

    public function generateTransactionCode()
    {
        $db = \Config\Database::connect();
        $date = date('Ymd');
        $prefix = 'TRX-' . $date . '-';

        $db->transStart();

        // Lock row dengan FOR UPDATE
        $query = $db->query("
            SELECT code 
            FROM transactions 
            WHERE code LIKE ?
            ORDER BY code DESC 
            LIMIT 1
            FOR UPDATE
        ", [$prefix . '%']);

        $row = $query->getRow();

        if ($row) {
            $lastNumber = (int) substr($row->code, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $sequence = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $newCode = $prefix . $sequence;

        $db->transComplete();

        return $newCode;
    }
}
