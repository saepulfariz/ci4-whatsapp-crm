<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Entities\Superadmin\AuthGroup;
use App\Entities\Superadmin\AuthPermission;
use App\Entities\Superadmin\AuthPermissionGroup;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\Group;

class AuthGroupsUsers extends BaseController
{
    private $model;
    private $model_user;
    private $model_auth_group;
    private $link = 'superadmin/auth-groups-users';
    private $view = 'superadmin/auth-groups-users';
    private $title = 'Auth Groups Users';
    public function __construct()
    {
        $this->model = new \CodeIgniter\Shield\Models\GroupModel;
        $this->model_user = new \CodeIgniter\Shield\Models\UserModel;
        $this->model_auth_group = new \App\Models\Superadmin\AuthGroupModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('group-user.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'groups_users' => $this->model->select('auth_groups_users.*, users.username as username')->join('users', 'users.id = auth_groups_users.user_id')->findAll()
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
        $redirect = checkPermission('group-user.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'users' => $this->model_user->findAll(),
            'groups' => $this->model_auth_group->findAll(),
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
        $redirect = checkPermission('group-user.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $rules = [
            'user_id' => 'required',
            'group' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();

        try {
            $group_user = new Group();

            $group_user->user_id =  htmlspecialchars($this->request->getVar('user_id'), true);
            $group_user->group =  htmlspecialchars($this->request->getVar('group'), true);

            $group_user->created_at = date('Y-m-d H:i:s');

            if (!$this->model->save($group_user)) {
                $modelErrors = $this->model->errors();
                log_message('error', 'Model Auth group users validation create failed: ' . json_encode($modelErrors));

                // Jika gagal simpan, rollback transaksi
                $this->db->transRollback();
                return false;
            }


            log_activity('create', $this->model->table, $this->model->getInsertID(), $group_user->toArray());

            if ($this->db->transStatus() === false) {
                $dbError = $this->db->error();
                log_message('error', 'Database AuthGroupUser create error: ' . json_encode($dbError));

                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to create group user')->withInput();
            }

            $this->db->transCommit();

            return redirect()->with('success', 'Group user created successfully.')->to($this->link);
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Exception authgroup create error: ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
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

        $redirect = checkPermission('group-user.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }


        $group_user = $this->model->find($id);

        if (!$group_user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // return redirect()->to($this->link);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'group_user' => $group_user,
            'users' => $this->model_user->findAll(),
            'groups' => $this->model_auth_group->findAll(),
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

        $redirect = checkPermission('group-user.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $group_user = $this->model->find($id);

        if (!$group_user) {
            return redirect()->to($this->link);
        }

        $rules = [
            'user_id' => 'required',
            'group' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();

        try {

            $data = [];

            $data['user_id'] =  htmlspecialchars($this->request->getVar('user_id'), true);
            $data['group'] =  htmlspecialchars($this->request->getVar('group'), true);

            $this->model->update($id, $data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to update group user')->withInput();
            }

            $this->db->transCommit();

            $lastData = is_object($group_user) ? $group_user->toArray() : $group_user;
            log_activity('update', $this->model->table, $id, array_diff_assoc($data, $lastData));

            return redirect()->with('success', 'Group user updated successfully.')->to($this->link);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to update group user')->withInput();
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
        $redirect = checkPermission('group-user.delete');
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
                return redirect()->back()->with('error', 'Failed to delete group user')->withInput();
            }

            $this->db->transCommit();

            $lastData = is_object($group_user) ? $group_user->toArray() : $group_user;
            log_activity('delete', $this->model->table, $id, $lastData);

            return redirect()->with('success', 'Group user deleted successfully.')->to($this->link);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to delete group user')->withInput();
        }
    }
}
