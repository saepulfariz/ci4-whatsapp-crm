<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Categories extends BaseController
{
    private $model;
    private $productModel;
    private $link = 'categories';
    private $view = 'categories';
    private $title = 'Categories';
    public function __construct()
    {
        $this->title = temp_lang('categories.categories');
        $this->model = new \App\Models\CategoryModel();
        $this->productModel = new \App\Models\ProductModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('categories.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'categories' => $this->model->select('categories.*, count(products.id) as product_count')->join('products', 'products.category_id = categories.id', 'left')->groupBy('categories.id')->orderBy('id', 'desc')->findAll()
        ];

        return view($this->view . '/index', $data);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        return redirect()->to($this->link);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        return redirect()->to($this->link);

        $redirect = checkPermission('categories.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
        ];

        return view($this->view . '/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $redirect = checkPermission('categories.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $rules = [
            'code' => 'required',
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();


        try {
            $data = [
                'code' => $this->request->getVar('code', FILTER_SANITIZE_STRING),
                'name' => $this->request->getVar('name', FILTER_SANITIZE_STRING),
                'description' => $this->request->getVar('description', FILTER_SANITIZE_STRING),
                'status' => $this->request->getVar('status', FILTER_SANITIZE_STRING),
            ];

            $this->model->insert($data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('categories.create_error'))->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success',  temp_lang('categories.create_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('categories.create_error'))->withInput();
        }
    }


    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        return redirect()->to($this->link);

        $redirect = checkPermission('categories.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $category = $this->model->find($id);

        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // return redirect()->to($this->link);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'category' => $category,
        ];

        return view($this->view . '/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $redirect = checkPermission('categories.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $category = $this->model->find($id);

        if (!$category) {
            return redirect()->to($this->link);
        }

        $rules = [
            'code' => 'required',
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }


        $this->db->transBegin();

        try {


            $data = [
                'code' => $this->request->getVar('code', FILTER_SANITIZE_STRING),
                'name' => $this->request->getVar('name', FILTER_SANITIZE_STRING),
                'description' => $this->request->getVar('description', FILTER_SANITIZE_STRING),
                'status' => $this->request->getVar('status', FILTER_SANITIZE_STRING),
            ];


            $this->model->update($id, $data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error',  temp_lang('categories.update_error'))->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', temp_lang('categories.update_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('categories.update_error'))->withInput();
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $redirect = checkPermission('categories.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $category = $this->model->find($id);

        if (!$category) {
            return redirect()->to($this->link);
        }

        $this->db->transBegin();

        try {

            // check exist use in data products
            $product = $this->productModel->select('id')->where('category_id', $id)->findAll();
            if ($product) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('categories.delete_error_used'))->withInput();
            }

            $this->model->delete($id);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('categories.delete_error'))->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', temp_lang('categories.delete_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('categories.delete_error'))->withInput();
        }
    }
}
