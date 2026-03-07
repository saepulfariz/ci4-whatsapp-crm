<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Entities\Superadmin\AuthGroup;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AuthGroups extends BaseController
{
    private $model;
    private $model_authpermission;
    private $model_authpermissiongroup;
    private $service_authgroup;
    private $link = 'superadmin/auth-groups';
    private $view = 'superadmin/auth-groups';
    private $title = 'Auth Groups';
    public function __construct()
    {
        $this->model = new \App\Models\Superadmin\AuthGroupModel();
        $this->service_authgroup = new \App\Services\Superadmin\AuthGroupService();
        $this->model_authpermission = new \App\Models\Superadmin\AuthPermissionModel();
        $this->model_authpermissiongroup = new \App\Models\Superadmin\AuthPermissionGroupModel();
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
            'groups' => $this->model->findAll()
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

        $data_permissions = $this->model_authpermission->findAll();

        // Misalnya, $data adalah array objek
        $permissions = [];

        foreach ($data_permissions as $permission) {
            // Ambil bagian sebelum titik pertama
            $prefix = explode('.', $permission->name)[0];

            // Kelompokkan data berdasarkan prefix
            if (!isset($permissions[$prefix])) {
                $permissions[$prefix] = [];
            }
            $permissions[$prefix][] = $permission;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'permissions' => $permissions
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

        $data = $this->request->getVar();

        $result = $this->service_authgroup->createAuthGroup($data);

        if ($result === false) {
            return redirect()->back()->with('error', 'Failed to create group')->withInput();
        }

        return redirect()->with('success', 'Group created successfully.')->to($this->link);
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

        $data_permissions = $this->model_authpermission->findAll();

        $permissions = [];
        foreach ($data_permissions as $permission) {
            // Ambil bagian sebelum titik pertama
            $prefix = explode('.', $permission->name)[0];

            // Kelompokkan data berdasarkan prefix
            if (!isset($permissions[$prefix])) {
                $permissions[$prefix] = [];
            }
            $permissions[$prefix][] = $permission;
        }

        $group_permissions = $data_permissions = $this->model_authpermissiongroup->where('group_id', $id)->findAll();

        $rolePermissions = array_column($group_permissions, 'permission');


        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'group' => $group,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
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

        $data = $this->request->getVar();

        $result = $this->service_authgroup->updateAuthGroup($id, $data);

        if ($result === false) {
            return redirect()->back()->with('error', 'Failed to update group')->withInput();
        }

        return redirect()->with('success', 'Group updated successfully.')->to($this->link);
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

        $result = $this->service_authgroup->deleteAuthGroup($id);

        if ($result === false) {
            return redirect()->back()->with('error', 'Failed to delete group')->withInput();
        }

        return redirect()->with('success', 'Group deleted successfully.')->to($this->link);
    }
}
