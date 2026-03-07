<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Entities\Superadmin\AuthGroup;
use App\Entities\Superadmin\AuthPermission;
use App\Entities\Superadmin\AuthPermissionGroup;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AuthPermissionsGroups extends BaseController
{
    private $model;
    private $model_auth_group;
    private $model_auth_permission;
    private $link = 'superadmin/auth-permissions-groups';
    private $view = 'superadmin/auth-permissions-groups';
    private $title = 'Auth Permissions Groups';
    public function __construct()
    {
        $this->model = new \App\Models\Superadmin\AuthPermissionGroupModel();
        $this->model_auth_group = new \App\Models\Superadmin\AuthGroupModel();
        $this->model_auth_permission = new \App\Models\Superadmin\AuthPermissionModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('permission-group.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'permissions_groups' => $this->model->select('auth_permissions_groups.*, auth_groups.name as group_name')->join('auth_groups', 'auth_groups.id = auth_permissions_groups.group_id')->orderBy('auth_permissions_groups.created_at', 'DESC')->findAll()
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
        $redirect = checkPermission('permission-group.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'groups' => $this->model_auth_group->findAll(),
            'permissions' => $this->model_auth_permission->findAll(),
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
        $redirect = checkPermission('permission-group.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $rules = [
            'group_id' => 'required',
            'permission' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();

        try {
            $permission_group = new AuthPermissionGroup();
            $permission_group->group_id = htmlspecialchars($this->request->getVar('group_id'), true);
            $permission_group->permission = htmlspecialchars($this->request->getVar('permission'), true);

            $this->model->save($permission_group);

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', 'Permission group created successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to create permission group')->withInput();
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
        $redirect = checkPermission('permission-group.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }


        $permission_group = $this->model->find($id);

        if (!$permission_group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // return redirect()->to($this->link);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'permission_group' => $permission_group,
            'groups' => $this->model_auth_group->findAll(),
            'permissions' => $this->model_auth_permission->findAll(),
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
        $redirect = checkPermission('permission-group.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }


        $permission_group = $this->model->find($id);

        if (!$permission_group) {
            return redirect()->to($this->link);
        }

        $rules = [
            'group_id' => 'required',
            'permission' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();

        try {
            $permission_group->group_id = htmlspecialchars($this->request->getVar('group_id'), true);
            $permission_group->permission = htmlspecialchars($this->request->getVar('permission'), true);

            $this->model->save($permission_group);

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', 'Permission group updated successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to update permission group')->withInput();
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
        $redirect = checkPermission('permission-group.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $permission_group = $this->model->find($id);

        if (!$permission_group) {
            return redirect()->to($this->link);
        }

        $this->db->transBegin();

        try {
            $this->model->delete($id);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to delete permission group')->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', 'Permission group deleted successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to delete permission group')->withInput();
        }
    }
}
