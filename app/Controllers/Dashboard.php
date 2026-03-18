<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\ProductModel;

class Dashboard extends BaseController
{
    protected $model_transaction;
    protected $model_product;

    public function __construct()
    {
        $this->model_transaction = new TransactionModel();
        $this->model_product = new ProductModel();
    }

    public function index(): string
    {
        $today_sales = $this->model_transaction->select('SUM(total_amount) as total_sales')->where('DATE(created_at)', date('Y-m-d'))->first();

        // total transaction today
        $today_transaction = $this->model_transaction->select('COUNT(*) as total_transaction')->where('DATE(created_at)', date('Y-m-d'))->first();

        // yesterday sales
        $yesterday_sales = $this->model_transaction->select('SUM(total_amount) as total_sales')->where('DATE(created_at)', date('Y-m-d', strtotime('-1 day')))->first();

        // yesterday transaction
        $yesterday_transaction = $this->model_transaction->select('COUNT(*) as total_transaction')->where('DATE(created_at)', date('Y-m-d', strtotime('-1 day')))->first();

        $transaction_history = $this->model_transaction->select('transactions.*, users.username as cashier_name')
            ->select('(SELECT SUM(td.qty) FROM transaction_details td WHERE td.transaction_id = transactions.id) as total_items')
            ->select('(SELECT pm.name FROM payments p JOIN payment_methods pm ON pm.id = p.method_id WHERE p.transaction_id = transactions.id LIMIT 1) as payment_method_name')
            ->join('users', 'users.id = transactions.cid', 'left')
            ->orderBy('transactions.id', 'DESC')
            ->limit(10)
            ->findAll();

        $stock_low  = $this->model_product->getAllProductQty();

        // filter stock low
        $stock_low = array_filter($stock_low, function ($item) {
            return $item->stock < $item->min_qty;
        });


        // best selling product
        $best_selling_product = $this->model_transaction->select('products.name, SUM(transaction_details.qty) as total_sold')
            ->join('transaction_details', 'transaction_details.transaction_id = transactions.id')
            ->join('products', 'products.id = transaction_details.product_id')
            ->where('DATE(transactions.created_at)', date('Y-m-d'))
            ->groupBy('products.id')
            ->orderBy('total_sold', 'DESC')
            ->limit(1)
            ->first();

        $weekly_revenue = $this->model_transaction->select('SUM(total_amount) as weekly_revenue')
            ->where('created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->where('deleted_at IS NULL')
            ->first();

        // ubah jadi query builder
        $gross_profit = $this->model_transaction->select('SUM(transaction_details.total_price) as revenue, SUM(transaction_details.cogs * transaction_details.qty) as total_cogs, SUM(transaction_details.total_price) - SUM(transaction_details.cogs * transaction_details.qty) as gross_profit, ((SUM(transaction_details.total_price) - SUM(transaction_details.cogs * transaction_details.qty)) / SUM(transaction_details.total_price)) * 100 as margin')
            ->join('transaction_details', 'transaction_details.transaction_id = transactions.id')
            ->where('transactions.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')
            ->where('transactions.status', 'completed')
            ->where('transactions.payment_status', 'paid')
            ->where('transactions.deleted_at IS NULL')
            ->first();


        // echo 'Login  - ' . auth()->user()->email . ' - <a href="' . base_url('logout') . '">logout</a> - ';
        $data = [
            'title' => 'Dashboard',
            'transaction_history' => $transaction_history,
            'stock_low' => $stock_low,
            'sales' => [
                'today' => $today_sales,
                'yesterday' => $yesterday_sales,
                'persen' => $yesterday_sales->total_sales > 0 ? ($today_sales->total_sales - $yesterday_sales->total_sales) / $yesterday_sales->total_sales * 100 : 0,
            ],
            'transaction' => [
                'today' => $today_transaction,
                'yesterday' => $yesterday_transaction,
                'gap' => $today_transaction->total_transaction - $yesterday_transaction->total_transaction,
            ],
            'best_selling_product' => $best_selling_product,
            'weekly_revenue' => $weekly_revenue,
            'gross_profit' => $gross_profit,
        ];
        return view('dashboard/index', $data);
    }

    public function salesThisWeek()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transactions');
        $builder->select("
            DATE(created_at) as date,
            SUM(total_amount) as total
        ");

        // Ambil input GET
        $startDate = $this->request->getGet('start_date'); // format: Y-m-d
        $endDate   = $this->request->getGet('end_date');   // format: Y-m-d

        // default: 7 hari terakhir
        if (!$startDate) $startDate = date('Y-m-d', strtotime('-6 days'));
        if (!$endDate) $endDate = date('Y-m-d');

        $builder->where('created_at >=', $startDate . ' 00:00:00');
        $builder->where('created_at <=', $endDate . ' 23:59:59');
        $builder->where('status', 'completed');
        $builder->where('payment_status', 'paid');

        $builder->groupBy("DATE(created_at)");
        $builder->orderBy("date", "ASC");

        $query = $builder->get()->getResult();


        // mapping default biar semua tanggal muncul walau total 0
        $days = [];
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        foreach ($period as $date) {
            $dayName = $date->format('l'); // Monday, Tuesday, ...
            $days[$dayName] = 0;
        }


        // Map hasil query
        foreach ($query as $row) {
            $dayName = date('l', strtotime($row->date));
            $days[$dayName] = (float) $row->total;
        }

        // return labels day and values
        $result = [
            'data' => [
                'labels' => array_keys($days),
                'data' => array_values($days)
            ]
        ];
        return $this->response->setJSON($result);
    }

    public function productCategoryDistribution()
    {
        $db = \Config\Database::connect();

        // Ambil input start_date & end_date (format: Y-m-d)
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-6 days'));
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');

        if (!$startDate || !$endDate) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'start_date and end_date are required'
            ], 400);
        }

        // Query: ambil semua kategori (LEFT JOIN) & sum total transaksi

        // fix karena kalau tanggal yang gak di transaction gak 0 malah muncul yang lain

        $subquery = "(select SUM(transactions.total_amount) as total from transactions JOIN transaction_details ON transactions.id = transaction_details.transaction_id JOIN products ON products.id = transaction_details.product_id WHERE transactions.status = 'completed' AND transactions.payment_status = 'paid' AND transactions.created_at >= '{$startDate} 00:00:00' AND transactions.created_at <= '{$endDate} 23:59:59' AND products.category_id = c.id) as total";
        $builder = $db->table('categories c');
        $builder->select('c.name as category');
        $builder->select($subquery);
        $builder->groupBy('c.id');
        $builder->orderBy('total', 'DESC');

        $query = $builder->get()->getResult();

        // Hitung total keseluruhan untuk persentase
        $grandTotal = array_sum(array_map(fn($row) => (float)$row->total, $query));

        $labels = [];
        $percentages = [];
        foreach ($query as $row) {
            $labels[] = $row->category;
            // Persentase, jika total keseluruhan = 0 → 0%
            $percentages[] = $grandTotal > 0 ? round((float)$row->total / $grandTotal * 100, 2) : 0;
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'percentages' => $percentages
        ]);
    }
}
