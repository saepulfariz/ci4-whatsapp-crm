<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Reports extends BaseController
{
    private $model_transaction;
    private $model_transaction_detail;
    private $model_product;
    private $model_category;

    private $link = 'reports';
    private $view = 'reports';
    private $title = 'Reports';


    public function __construct()
    {
        $this->title = temp_lang('reports.reports');

        $this->model_transaction = new \App\Models\TransactionModel();
        $this->model_transaction_detail = new \App\Models\TransactionDetailModel();
        $this->model_product = new \App\Models\ProductModel();
        $this->model_category = new \App\Models\CategoryModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('reports.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $category_id = $this->request->getVar('category_id') ?? null;
        $start_date = $this->request->getVar('start_date') ?? null;
        $end_date = $this->request->getVar('end_date') ?? null;
        $report_type = $this->request->getVar('report_type') ?? null;
        $tab = $this->request->getVar('tab') ?? 'sales';


        $report_sales = $this->model_transaction_detail
            ->select('transaction_details.*, transaction_details.subtotal as total_price, transactions.code as transaction_code, products.name as product_name, products.price as product_price, products.cogs as product_cogs, categories.name as category_name')
            ->select('(transaction_details.subtotal - (transaction_details.cogs * transaction_details.qty)  ) as gross_profit')
            ->join('transactions', 'transactions.id = transaction_details.transaction_id')
            ->join('products', 'products.id = transaction_details.product_id')
            ->join('categories', 'categories.id = products.category_id')->orderBy('transaction_details.created_at', 'DESC');

        if ($category_id) {
            $report_sales = $report_sales->where('products.category_id', $category_id);
        }

        if ($start_date && $end_date) {
            $report_sales = $report_sales->where('transactions.created_at >=', $start_date)->where('transactions.created_at <=', $end_date);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'requests' => [
                'tab' => $tab,
                'report_type' => $report_type,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'category_id' => $category_id,
            ],
            'report_sales' => $report_sales->findAll(),
            'categories' => $this->model_category->findAll(),
        ];

        return view($this->view . '/index', $data);
    }
}
