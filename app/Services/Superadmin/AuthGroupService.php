<?php

namespace App\Services\Superadmin;

use App\Entities\Superadmin\AuthGroup;
use App\Models\Superadmin\AuthGroupModel;
use App\Models\Superadmin\AuthMenuModel;
use Exception;

class AuthGroupService
{
    protected $db;
    protected $model;
    protected $model_auth_menu;
    protected $model_authpermissiongroup;
    protected $validation;

    public function __construct()
    {
        $this->model = new AuthGroupModel();
        $this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect();
        $this->model_authpermissiongroup = new \App\Models\Superadmin\AuthPermissionGroupModel();

        $this->model_auth_menu = new AuthMenuModel();
    }

    // Mendapatkan semua produk
    public function getAllAuthGroups()
    {
        return $this->model->findAll();
    }

    // Mendapatkan produk berdasarkan ID
    public function getAuthGroupById($id)
    {
        return $this->model->find($id);
    }

    // Validasi dan menambahkan produk
    public function createAuthGroup($data)
    {
        $rules = [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'permissions' => 'required|is_array',
        ];

        // Validasi data
        if (!$this->validation->setRules($rules)->run($data)) {
            // return $this->validation->getErrors();
            return false;
        }

        $data = [
            'name' => htmlspecialchars($data['name'], true),
            'title' => htmlspecialchars($data['title'], true),
            'description' => htmlspecialchars($data['description'], true),
            'permissions' => $data['permissions'],
        ];

        $this->db->transBegin();


        try {
            $authgroup = new AuthGroup();

            $authgroup->fill($data);

            if (!$this->model->save($authgroup)) {

                $modelErrors = $this->model->errors();
                log_message('error', 'Model AuthGroup validation create failed: ' . json_encode($modelErrors));

                // Jika gagal simpan, rollback transaksi
                $this->db->transRollback();
                return false;
            }

            // get last id authgroup
            $authgroupId = $this->model->getInsertID();

            // Simpan ke tabel auth_permission_groups
            $permissionGroupsData = [];
            foreach ($data['permissions'] as $permission) {
                $permissionGroupsData[] = [
                    'group_id' => $authgroupId,
                    'permission' => $permission,
                ];
            }

            if (!empty($permissionGroupsData)) {
                if (!$this->model_authpermissiongroup->insertBatch($permissionGroupsData)) {
                    $modelErrors = $this->model_authpermissiongroup->errors();
                    log_message('error', 'Model AuthPermissionGroup validation create failed: ' . json_encode($modelErrors));

                    // Jika gagal simpan, rollback transaksi
                    $this->db->transRollback();
                    return false;
                }
            }

            if ($this->db->transStatus() === false) {

                $dbError = $this->db->error();
                log_message('error', 'Database AuthGroup create error: ' . json_encode($dbError));

                $this->db->transRollback();
                return false;
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            $cache->delete($this->model_authpermissiongroup->cacheKey); // Hapus cache

            $cache->delete($this->model_auth_menu->cacheKey);

            return $authgroup;
        } catch (Exception $e) {
            log_message('error', 'Exception authgroup create error: ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->db->transRollback();
            return false;
        }
    }

    // Validasi dan memperbarui produk
    public function updateAuthGroup($id, $data)
    {

        $authgroup = $this->model->find($id);

        if (!$authgroup) {
            return false;
        }

        $rules = [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'permissions' => 'required|is_array',
        ];

        if (!$this->validation->setRules($rules)->run($data)) {
            return false;
        }

        $permissions = $data['permissions'] ?? [];
        $data = [
            'name' => htmlspecialchars($data['name'], true),
            'title' => htmlspecialchars($data['title'], true),
            'description' => htmlspecialchars($data['description'], true),
        ];

        $this->db->transBegin();

        try {
            $authgroup->fill($data);

            $oldData = [
                'name' => $authgroup->name,
                'title' => $authgroup->title,
                'description' => $authgroup->description,
            ];

            if ($oldData != $data) {
                if (!$this->model->save($authgroup)) {

                    $modelErrors = $this->model->errors();
                    log_message('error', 'Model AuthGroup validation update failed: ' . json_encode($modelErrors));

                    $this->db->transRollback();
                    return false;
                }
            }


            // delete $this->model_authpermissiongroup where group_id = $id
            $this->model_authpermissiongroup->where('group_id', $id)->delete();

            // Simpan ke tabel auth_permission_groups
            $permissionGroupsData = [];
            foreach ($permissions as $permission) {
                $permissionGroupsData[] = [
                    'group_id' => $id,
                    'permission' => $permission,
                ];
            }

            if (!empty($permissionGroupsData)) {
                if (!$this->model_authpermissiongroup->insertBatch($permissionGroupsData)) {
                    $modelErrors = $this->model_authpermissiongroup->errors();
                    log_message('error', 'Model AuthPermissionGroup validation create failed: ' . json_encode($modelErrors));

                    // Jika gagal simpan, rollback transaksi
                    $this->db->transRollback();
                    return false;
                }
            }

            if ($this->db->transStatus() === false) {

                $dbError = $this->db->error();
                log_message('error', 'Database AuthGroup update error: ' . json_encode($dbError));

                $this->db->transRollback();
                return false;
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            $cache->delete($this->model_authpermissiongroup->cacheKey); // Hapus cache

            $cache->delete($this->model_auth_menu->cacheKey);

            return $authgroup;
        } catch (Exception $e) {
            log_message('error', 'Exception authgroup update error: ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            return false;
        }
    }

    // Menghapus produk
    public function deleteAuthGroup($id)
    {
        $authgroup = $this->model->find($id);

        if (!$authgroup) {
            return false;
        }

        $this->db->transBegin();

        try {

            $this->model_authpermissiongroup->where('group_id', $id)->delete();
            if (!$this->model_authpermissiongroup->db->transStatus()) {
                $modelErrors = $this->model_authpermissiongroup->errors();
                log_message('error', 'Model AuthPermissionGroup validation delete failed: ' . json_encode($modelErrors));

                $this->db->transRollback();
                return false;
            }

            if (!$this->model->delete($id)) {
                $modelErrors = $this->model->errors();
                log_message('error', 'Model AuthGroup validation delete failed: ' . json_encode($modelErrors));

                $this->db->transRollback();
                return false;
            }

            if ($this->db->transStatus() === false) {

                $dbError = $this->db->error();
                log_message('error', 'Database AuthGroup create error: ' . json_encode($dbError));

                $this->db->transRollback();
                return false;
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            $cache->delete($this->model_authpermissiongroup->cacheKey); // Hapus cache

            $cache->delete($this->model_auth_menu->cacheKey);

            return $authgroup;
        } catch (Exception $e) {
            log_message('error', 'Exception authgroup delete error: ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->db->transRollback();
            return false;
        }
    }
}
