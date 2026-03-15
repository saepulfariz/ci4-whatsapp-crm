<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Products extends BaseController
{
    private $model;
    private $model_category;
    private $model_product_stock;

    protected $uploadPath;
    protected $defaultImage;

    private $link = 'products';
    private $view = 'products';
    private $title = 'Products';
    public function __construct()
    {
        $this->title = temp_lang('products.products');
        $this->model = new \App\Models\ProductModel();
        $this->model_category = new \App\Models\CategoryModel();
        $this->model_product_stock = new \App\Models\ProductStockModel();

        $this->uploadPath = WRITEPATH . 'uploads/products/';

        $defaultImage = FCPATH . 'assets/dist/img/product.png';

        // $newName = 'user_' . time() . '.png';
        $this->defaultImage =  'product.png';

        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0775, true);
        }

        if (!file_exists($this->uploadPath . $this->defaultImage)) {
            copy($defaultImage, $this->uploadPath . $this->defaultImage);
        }
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('products.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $category_id = $this->request->getVar('category_id') ?? null;

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'category_id' => $category_id,
            'products' => $this->model->getAllProductQty($category_id),
            'categories' => $this->model_category->findAll(),
            'stocks' => $this->model_product_stock->findAll(),
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

        $redirect = checkPermission('products.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'categories' => $this->model_category->findAll(),
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
        $redirect = checkPermission('products.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $image = $this->request->getFile('image');

        $rules = [
            'category_id' => 'required',
            'code' => 'required',
            'name' => 'required',
            'price' => 'required',
            'cogs' => 'required',
            'qty' => 'required',
            'min_qty' => 'required',
            'status' => 'required',
            // 'description' => 'required',
        ];

        $input = $this->request->getVar();

        // Validasi hanya jika file di-upload
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $rules['image'] = [
                'label' => 'image image',
                'rules' => 'uploaded[image]'
                    . '|is_image[image]'
                    . '|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]'
                    . '|max_size[image,800]',
                'errors' => [
                    'uploaded' => 'Silahkan pilih file gambar terlebih dahulu.',
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in'  => 'Format harus JPG, JPEG, PNG atau GIF.',
                    'max_size' => 'Ukuran maksimal 800 KB.',
                ]
            ];
        }

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();


        try {
            $data = [
                'category_id' => $this->request->getVar('category_id', FILTER_SANITIZE_NUMBER_INT),
                'code' => $this->request->getVar('code', FILTER_SANITIZE_STRING),
                'name' => $this->request->getVar('name', FILTER_SANITIZE_STRING),
                'price' => $this->request->getVar('price', FILTER_SANITIZE_STRING),
                'cogs' => $this->request->getVar('cogs', FILTER_SANITIZE_STRING),
                'qty' => $this->request->getVar('qty', FILTER_SANITIZE_STRING),
                'min_qty' => $this->request->getVar('min_qty', FILTER_SANITIZE_STRING),
                'description' => $this->request->getVar('description', FILTER_SANITIZE_STRING),
                'status' => $this->request->getVar('status', FILTER_SANITIZE_STRING),
            ];

            // Jika ada upload file
            if ($image && $image->isValid() && !$image->hasMoved()) {

                // Generate nama random
                $imageName = $image->getRandomName();

                // Simpan ke folder writable/uploads
                $image->move($this->uploadPath, $imageName);

                // Simpan nama file ke database
                $data['image'] = $imageName;
            } else {
                $data['image'] = $this->defaultImage;
            }

            $this->model->insert($data);

            // product stock
            $stock = [
                'product_id' => $this->model->getInsertID(),
                'qty' => $data['qty'],
                'current_stock' => $data['qty'],
                'prev_stock' => 0,
                'note' => '',
                'date' => date('Y-m-d H:i:s'),
            ];

            $this->model_product_stock->save($stock);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('products.create_error'))->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success',  temp_lang('products.create_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('products.create_error'))->withInput();
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

        $redirect = checkPermission('products.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $product = $this->model->find($id);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // return redirect()->to($this->link);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'product' => $product,
            'categories' => $this->model_category->findAll()
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
        $redirect = checkPermission('products.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $image = $this->request->getFile('image');

        $product = $this->model->find($id);

        if (!$product) {
            return redirect()->to($this->link);
        }

        $rules = [
            'category_id' => 'required',
            'code' => 'required',
            'name' => 'required',
            'price' => 'required',
            'cogs' => 'required',
            'qty' => 'required',
            'min_qty' => 'required',
            // 'description' => 'required',
        ];

        $input = $this->request->getVar();

        // Validasi hanya jika file di-upload
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $rules['image'] = [
                'label' => 'image image',
                'rules' => 'uploaded[image]'
                    . '|is_image[image]'
                    . '|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]'
                    . '|max_size[image,800]',
                'errors' => [
                    'uploaded' => 'Silahkan pilih file gambar terlebih dahulu.',
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in'  => 'Format harus JPG, JPEG, PNG atau GIF.',
                    'max_size' => 'Ukuran maksimal 800 KB.',
                ]
            ];
        }

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }


        $this->db->transBegin();

        try {


            $data = [
                'category_id' => $this->request->getVar('category_id', FILTER_SANITIZE_NUMBER_INT),
                'code' => $this->request->getVar('code', FILTER_SANITIZE_STRING),
                'name' => $this->request->getVar('name', FILTER_SANITIZE_STRING),
                'price' => $this->request->getVar('price', FILTER_SANITIZE_STRING),
                'cogs' => $this->request->getVar('cogs', FILTER_SANITIZE_STRING),
                'qty' => $this->request->getVar('qty', FILTER_SANITIZE_STRING),
                'min_qty' => $this->request->getVar('min_qty', FILTER_SANITIZE_STRING),
                'description' => $this->request->getVar('description', FILTER_SANITIZE_STRING),
                'status' => $this->request->getVar('status', FILTER_SANITIZE_STRING),
            ];

            // Jika ada upload file
            if ($image && $image->isValid() && !$image->hasMoved()) {

                $oldimage = $product->image;

                if (!empty($oldimage) && $oldimage != $this->defaultImage && file_exists($this->uploadPath . $oldimage)) {
                    unlink($this->uploadPath . $oldimage);
                }

                // Generate nama random
                $imageName = $image->getRandomName();

                // Simpan ke folder writable/uploads
                $image->move($this->uploadPath, $imageName);

                // Simpan nama file ke database
                $data['image'] = $imageName;
            }


            $this->model->update($id, $data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error',  temp_lang('products.update_error'))->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', temp_lang('products.update_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('products.update_error'))->withInput();
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
        $redirect = checkPermission('products.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $product = $this->model->find($id);

        if (!$product) {
            return redirect()->to($this->link);
        }

        $this->db->transBegin();

        try {
            $this->model->delete($id);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('products.delete_error'))->withInput();
            }

            $this->db->transCommit();

            $oldImage = $product->image;
            if (!empty($oldImage) && $oldImage != $this->defaultImage && file_exists($this->uploadPath . $oldImage)) {
                unlink($this->uploadPath . $oldImage);
            }

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', temp_lang('products.delete_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('products.delete_error'))->withInput();
        }
    }


    function activate($id = null)
    {
        $redirect = checkPermission('products.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $product = $this->model->find($id);

        if (!$product) {
            return redirect()->to($this->link);
        }

        $this->model->update($id, ['is_active' => 1]);

        $cache = \Config\Services::cache();
        $cache->delete($this->model->cacheKey);

        return redirect()->with('success', temp_lang('products.activate_success'))->to($this->link);
    }

    function deactivate($id = null)
    {
        $redirect = checkPermission('products.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }


        $product = $this->model->find($id);

        if (!$product) {
            return redirect()->to($this->link);
        }

        $this->model->update($id, ['is_active' => 0]);

        $cache = \Config\Services::cache();
        $cache->delete($this->model->cacheKey);

        return redirect()->with('success', temp_lang('products.deactivate_success'))->to($this->link);
    }
}
