<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Models\UserIdentityModel;

class Users extends BaseController
{

    private $model;
    private $model_user_identity;
    private $link = 'superadmin/users';
    private $view = 'superadmin/users';
    private $title = 'Users';
    public function __construct()
    {
        $this->model = auth()->getProvider();
        $this->model_user_identity = new UserIdentityModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('users.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'users' => $this->model->select('users.*, auth_identities.name as name, auth_identities.secret as email_user')->join('auth_identities', 'auth_identities.user_id = users.id')
                ->orderBy('created_at', 'DESC')->findAll()
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
        $redirect = checkPermission('users.create');
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
        checkPermission('users.create');

        $rules = [
            'name' => 'required',
            'password' => 'required|min_length[8]',
            'email' => 'required|is_unique[auth_identities.secret]|valid_email',
            'username' => 'required|is_unique[users.username]',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();

        try {
            $users = $this->model;

            $user = new User([
                'username' => htmlspecialchars($this->request->getVar('username')),
                'email'    => htmlspecialchars($this->request->getVar('email')),
                'password' => $this->request->getVar('password'),
            ]);
            $users->save($user);

            $update_indentity = [
                'name' => htmlspecialchars($this->request->getVar('name')),
            ];

            $this->model_user_identity->update($users->getInsertID(), $update_indentity);

            // To get the complete user object with ID, we need to get from the database
            // $users->getInsertID() id insert
            $user = $users->findById($users->getInsertID());

            // Add to default group
            $users->addToDefaultGroup($user);

            // If the transaction fails, rollback
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to create user')->withInput();
            }

            $this->db->transCommit();
            return redirect()->with('success', 'User created successfully.')->to($this->link);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to create user')->withInput();
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
        checkPermission('users.edit');

        // Get the User Provider (UserModel by default)
        $users = $this->model;

        // Find by the user_id
        $user = $users->select('users.*, auth_identities.name as name')->join('auth_identities', 'auth_identities.user_id = users.id')->findById($id);
        // Find by the user email
        // $user = $users->findByCredentials(['email' => '[email protected]']);


        if (!$user) {
            return redirect()->to($this->link);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'user' => $user,
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
        checkPermission('users.edit');

        // Get the User Provider (UserModel by default)
        $users = $this->model;

        $user = $users->findById($id);

        if (!$user) {
            return redirect()->to($this->link);
        }

        $rules = [
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
        ];

        $input = $this->request->getVar();

        if ($input['email'] != $user->email) {
            $rules['email'] = 'required|is_unique[auth_identities.secret]|valid_email';
        }

        if ($input['username'] != $user->username) {
            $rules['username'] = 'required|is_unique[users.username]';
        }

        if ($input['password'] != '') {
            $rules['password'] = 'required|min_length[8]';
        }


        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $password = $this->request->getVar('password');

        $data = [
            'username' => htmlspecialchars($this->request->getVar('username')),
            'email'    => htmlspecialchars($this->request->getVar('email')),
        ];

        if ($password) {
            $data['password'] = $password;
        }

        $this->db->transBegin();

        try {
            $user->fill($data);
            $users->save($user);

            $update_indentity = [
                'name' => htmlspecialchars($this->request->getVar('name')),
            ];

            $this->model_user_identity->update($id, $update_indentity);

            // If the transaction fails, rollback

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to update user')->withInput();
            }

            $this->db->transCommit();

            return redirect()->with('success', 'User updated successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to update user')->withInput();
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
        checkPermission('users.delete');

        $users = auth()->getProvider();

        $user = $users->findById($id);

        if (!$user) {
            return redirect()->to($this->link);
        }

        $this->db->transBegin();

        try {
            $users->delete($id, true);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to delete user')->withInput();
            }

            $this->db->transCommit();

            return redirect()->with('success', 'User deleted successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to delete user')->withInput();
        }
    }



    public function banned($id = null)
    {
        if ($id == null) {
            return redirect()->to($this->link);
        }

        $users = auth()->getProvider();

        $user = $users->findById($id);

        if (!$user) {
            return redirect()->to($this->link);
        }

        if ($user->isBanned()) {
            $user->unBan();
            $message = 'User unbanned successfully.';
        } else {
            $message = 'User banned successfully.';
            $user->ban('Banned This User');
        }

        return redirect()->with('success', $message)->to($this->link);
    }
}
