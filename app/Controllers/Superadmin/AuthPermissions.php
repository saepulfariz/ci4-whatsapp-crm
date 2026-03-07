<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Entities\Superadmin\AuthGroup;
use App\Entities\Superadmin\AuthPermission;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AuthPermissions extends BaseController
{
    private $model;
    private $link = 'superadmin/auth-permissions';
    private $view = 'superadmin/auth-permissions';
    private $title = 'Auth Permissions';
    public function __construct()
    {
        $this->model = new \App\Models\Superadmin\AuthPermissionModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('permissions.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'permissions' => $this->model->findAll()
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
        $redirect = checkPermission('permissions.create');
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
        $redirect = checkPermission('permissions.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $rules = [
            'name' => 'required',
            'title' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();

        try {
            $permission = new AuthPermission();
            $permission->name = htmlspecialchars($this->request->getVar('name'), true);
            $permission->title = htmlspecialchars($this->request->getVar('title'), true);

            $this->model->save($permission);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                return redirect()->back()->with('error', 'Failed to create permission. Please try again.')->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->to($this->link)->with('success', 'Permission created successfully.');
        } catch (\Throwable $th) {
            //throw $th;

            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to create permission')->withInput();
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
        $redirect = checkPermission('permissions.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $permission = $this->model->find($id);

        if (!$permission) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // return redirect()->to($this->link);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'permission' => $permission,
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
        $redirect = checkPermission('permissions.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $permission = $this->model->find($id);

        if (!$permission) {
            return redirect()->to($this->link);
        }

        $rules = [
            'name' => 'required',
            'title' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }


        $this->db->transBegin();

        try {

            $permission->name = htmlspecialchars($this->request->getVar('name'), true);
            $permission->title = htmlspecialchars($this->request->getVar('title'), true);

            $this->model->save($permission);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                return redirect()->back()->with('error', 'Failed to update permission. Please try again.')->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', 'Permission updated successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to update permission')->withInput();
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
        $redirect = checkPermission('permissions.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $permission = $this->model->find($id);

        if (!$permission) {
            return redirect()->to($this->link);
        }

        // Check if the permission is used by any group
        $groupModel = new \App\Models\Superadmin\AuthPermissionGroupModel();
        $group = $groupModel->like('permission', $permission->name)->limit(1)->first();

        if ($group) {
            return redirect()->back()->with('error', 'Permission cannot be deleted because it is used by a group.')->withInput();
        }

        $this->db->transBegin();

        try {

            $this->model->delete($id);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                return redirect()->back()->with('error', 'Failed to delete permission. Please try again.')->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', 'Permission deleted successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            // $th->getMessage()
            return redirect()->back()->with('error', 'Failed to delete permission')->withInput();
        }
    }
}
