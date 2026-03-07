<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Models\UserIdentityModel;

class Profile extends BaseController
{

    private $model;
    private $model_user_identity;
    private $link = 'profile';
    private $view = 'superadmin/profile';
    private $title = 'Profile';
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
        // $redirect = checkPermission('profile.access');
        // if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
        //     return $redirect;
        // }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'profile' => getProfile()
        ];

        return view($this->view . '/index', $data);
    }


    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update()
    {
        // checkPermission('profile.edit');

        $id = auth()->id();

        // Get the User Provider (UserModel by default)
        $profile = $this->model;

        $user = $profile->findById($id);

        if (!$user) {
            return redirect()->to($this->link);
        }

        $rules = [
            'name' => 'required',
            'email' => 'required',
        ];

        $input = $this->request->getVar();

        if ($input['email'] != $user->email) {
            $rules['email'] = 'required|is_unique[auth_identities.secret]|valid_email';
        }


        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $data = [
            'email'    => htmlspecialchars($this->request->getVar('email')),
        ];


        $this->db->transBegin();

        try {
            $user->fill($data);
            $profile->save($user);

            $old_indentity = $this->model_user_identity->find($id)->toArray();

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

            $cache = \Config\Services::cache();
            $cacheKey = 'auth_user_' . auth()->id();
            $cache->delete($cacheKey);

            log_activity('update', $profile->table, $id, array_diff_assoc($data, $user->toArray()));
            log_activity('update', $this->model_user_identity->table, $id, array_diff_assoc($update_indentity, $old_indentity));

            return redirect()->with('success', 'User updated successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to update user')->withInput();
        }
    }


    public function changePassword()
    {
        // $redirect = checkPermission('profile.change-password');
        // if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
        //     return $redirect;
        // }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
        ];

        return view($this->view . '/change-password', $data);
    }

    public function updatePassword()
    {
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'new_password_confirm' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword     = $this->request->getPost('new_password');

        $id = auth()->id();

        // Get the User Provider (UserModel by default)
        $profile = $this->model;

        $user = $profile->findById($id);

        if (!$user) {
            return redirect()->to($this->link);
        }

        // Validasi current password
        if (!password_verify($currentPassword, $user->getPasswordHash())) {
            return redirect()->back()->withInput()->with('_ci_validation_errors', ['current_password' => 'Current password is incorrect.'])->with('error', 'Current password is incorrect.');
        }

        $data = [
            'password' => $newPassword,
        ];

        $user->fill($data);
        $profile->save($user);

        $newUser = $profile->findById($id);

        $data = [
            'password' => $newUser->getPasswordHash(),
        ];

        log_activity('update', $profile->table, $id, array_diff_assoc($data, $user->toArray()));

        return redirect()->with('success', 'Password updated successfully.')->to('change-password');
    }
}
