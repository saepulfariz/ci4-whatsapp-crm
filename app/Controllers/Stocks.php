<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Stocks extends BaseController
{
    private $model;
    private $model_product;

    private $link = 'stocks';
    private $view = 'stocks';
    private $title = 'Stocks';
    public function __construct()
    {
        $this->title = temp_lang('stocks.stocks');
        $this->model = new \App\Models\ProductStockModel();
        $this->model_product = new \App\Models\ProductModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('stocks.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $type = $this->request->getVar('type') ?? null;

        $stock = $this->model->select('product_stocks.*, products.name as product_name, products.code as product_code, users.username as input_by')->join('products', 'products.id = product_stocks.product_id')->join('users', 'users.id = product_stocks.cid')->orderBy('product_stocks.id', 'DESC');
        if ($type) {
            $stock->where('type', $type);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'type_id' => $type,
            'types' => $this->model->types,
            'products' => $this->model_product->findAll(),
            'stocks' => $stock->findAll()
        ];

        return view($this->view . '/index', $data);
    }


    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $redirect = checkPermission('stocks.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $rules = [
            'product_id' => 'required',
            'type' => 'required',
            'qty' => 'required',
            'note' => 'required',
        ];

        $input = $this->request->getVar();


        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();


        try {
            $product = $this->model_product->find($this->request->getVar('product_id', FILTER_SANITIZE_NUMBER_INT));

            if (!$product) {
                return redirect()->back()->withInput();
            }


            $data = [
                'product_id' => $this->request->getVar('product_id'),
                'type' => $this->request->getVar('type'),
                'qty' => $this->request->getVar('qty'),
                'prev_stock' => $product->qty,
                'note' => $this->request->getVar('note'),
                'date' => $this->request->getVar('date'),
            ];

            if ($data['type'] == 'Stock In' || $data['type'] == 'Adjustment Increase') {
                $current_stock = $product->qty + $data['qty'];
                $data['current_stock'] = $current_stock;
            } else {
                $current_stock = $product->qty - $data['qty'];
                $data['current_stock'] = $current_stock;
            }

            $stock = new \App\Entities\ProductStock();
            $stock->fill($data);

            $this->model->save($stock);

            // update product
            $this->model_product->update($data['product_id'], [
                'qty' => $current_stock
            ]);


            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('stocks.create_error'))->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success',  temp_lang('stocks.create_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('stocks.create_error'))->withInput();
        }
    }
}
