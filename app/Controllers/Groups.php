<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Groups extends BaseController
{
    private $model;
    private $model_customer;

    private $link = 'customers';
    private $view = 'groups';
    private $title = 'Groups';
    public function __construct()
    {
        $this->title = temp_lang('groups.groups');
        $this->model = new \App\Models\GroupModel();
        $this->model_customer = new \App\Models\CustomerModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('groups.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'groups' => $this->model->orderBy('id', 'desc')->findAll(),
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
        $redirect = checkPermission('groups.create');
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
        $redirect = checkPermission('groups.create');
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
                return redirect()->back()->with('error', temp_lang('groups.create_error'))->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success',  temp_lang('groups.create_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('groups.create_error'))->withInput();
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
        $redirect = checkPermission('groups.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $group = $this->model->find($id);

        if (!$group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // return redirect()->to($this->link);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'group' => $group,
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
        $redirect = checkPermission('groups.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $group = $this->model->find($id);

        if (!$group) {
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
                return redirect()->back()->with('error',  temp_lang('groups.update_error'))->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', temp_lang('groups.update_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('groups.update_error'))->withInput();
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
        $redirect = checkPermission('groups.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $group = $this->model->find($id);

        if (!$group) {
            return redirect()->to($this->link);
        }

        $this->db->transBegin();

        try {

            // check use in customers   
            $customer = $this->model_customer->where('group_id', $id)->first();
            if ($customer) {
                return redirect()->back()->with('error', temp_lang('groups.group_used_in_customers'))->withInput();
            }

            $this->model->delete($id);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', temp_lang('groups.delete_error'))->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', temp_lang('groups.delete_success'))->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', temp_lang('groups.delete_error'))->withInput();
        }
    }
}
