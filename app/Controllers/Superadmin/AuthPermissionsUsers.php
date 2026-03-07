<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Entities\Superadmin\AuthGroup;
use App\Entities\Superadmin\AuthPermission;
use App\Entities\Superadmin\AuthPermissionGroup;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\Group;
use Exception;

class AuthPermissionsUsers extends BaseController
{
    private $model;
    private $model_user;
    private $model_auth_permission;
    private $link = 'superadmin/auth-permissions-users';
    private $view = 'superadmin/auth-permissions-users';
    private $title = 'Auth Groups Users';
    public function __construct()
    {
        $this->model = new \CodeIgniter\Shield\Models\PermissionModel;
        $this->model_user = new \CodeIgniter\Shield\Models\UserModel;
        $this->model_auth_permission = new \App\Models\Superadmin\AuthPermissionModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('permission-user.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'permissions_users' => $this->model->select('auth_permissions_users.*, users.username as username')->join('users', 'users.id = auth_permissions_users.user_id')->findAll()
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
        $redirect = checkPermission('permission-user.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'users' => $this->model_user->findAll(),
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
        $redirect = checkPermission('permission-user.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $rules = [
            'user_id' => 'required',
            'permission' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();

        try {
            $data = [];

            $data['user_id'] =  htmlspecialchars($this->request->getVar('user_id'), true);
            $data['permission'] =  htmlspecialchars($this->request->getVar('permission'), true);

            $data['created_at'] = date('Y-m-d H:i:s');

            if (!$this->model->save($data)) {
                $modelErrors = $this->model->errors();
                log_message('error', 'Model Auth Permission User validation create failed: ' . json_encode($modelErrors));
            }

            if ($this->db->transStatus() === false) {

                $dbError = $this->db->error();
                log_message('error', 'Database Auth Permission User create error: ' . json_encode($dbError));

                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to create permission user')->withInput();
            }

            log_activity('create', $this->model->table, $this->model->getInsertID(), $data);

            $this->db->transCommit();

            return redirect()->with('success', 'Permission user created successfully.')->to($this->link);
        } catch (Exception $e) {
            log_message('error', 'Exception request create error: ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to create permission user')->withInput();
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
        $redirect = checkPermission('permission-user.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $permission_user = $this->model->find($id);

        if (!$permission_user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // return redirect()->to($this->link);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'permission_user' => $permission_user,
            'users' => $this->model_user->findAll(),
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
        $redirect = checkPermission('permission-user.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $permission_user = $this->model->find($id);

        if (!$permission_user) {
            return redirect()->to($this->link);
        }

        $rules = [
            'user_id' => 'required',
            'permission' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();

        try {
            $data = [];

            $data['user_id'] =  htmlspecialchars($this->request->getVar('user_id'), true);
            $data['permission'] =  htmlspecialchars($this->request->getVar('permission'), true);

            $this->model->update($id, $data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to update permission user')->withInput();
            }

            $this->db->transCommit();

            // d($data);
            // dd($data);

            // log_activity('update', $this->model->table, $id, array_diff_assoc($data, $permission_user->toArray()));

            return redirect()->with('success', 'Permission user updated successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to update permission user')->withInput();
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
        $redirect = checkPermission('permission-user.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $group_user = $this->model->find($id);

        if (!$group_user) {
            return redirect()->to($this->link);
        }

        $this->db->transBegin();

        try {
            $this->model->delete($id);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to delete permission user')->withInput();
            }

            $this->db->transCommit();

            log_activity('delete', $this->model->table, $id, $group_user->toArray());

            return redirect()->with('success', 'Permission user deleted successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to delete permission user')->withInput();
        }
    }
}
