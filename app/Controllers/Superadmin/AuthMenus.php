<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Entities\Superadmin\AuthGroup;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AuthMenus extends BaseController
{
    private $model;
    private $model_auth_permission;
    private $link = 'superadmin/auth-menus';
    private $view = 'superadmin/auth-menus';
    private $title = 'Auth Menus';
    public function __construct()
    {
        $this->model = new \App\Models\Superadmin\AuthMenuModel();
        $this->model_auth_permission = new \App\Models\Superadmin\AuthPermissionModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $redirect = checkPermission('menus.access');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'menus' => $this->model->select('auth_menus.*, other_menus.title as parent_title')->join('auth_menus as other_menus', 'other_menus.id = auth_menus.parent_id', 'left')->findAll()
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
        $redirect = checkPermission('menus.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'menus' => $this->model->findAll(),
            'permissions' => $this->model_auth_permission->findAll(),
        ];

        return view($this->view . '/new', $data);
    }

    public function order()
    {
        $allMenus = $this->model->where('active', 1)->orderBy('order', 'ASC')->findAll();
        $menuHierarchy = $this->buildMenuHierarchy($allMenus);

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'menus' => $menuHierarchy,
        ];

        return view($this->view . '/order', $data);
    }

    /**
     * Menerima data urutan dan parent menu dari frontend (AJAX)
     */
    public function updateOrder()
    {
        if ($this->request->isAJAX()) {
            $menuData = $this->request->getJSON(true); // Ambil data JSON dari request

            if (empty($menuData)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'The menu data is empty']);
            }

            $this->processMenuUpdates($menuData);

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Menu order successfully updated.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
    }

    /**
     * Membangun struktur menu hirarkis dari flat list.
     * Digunakan untuk menampilkan menu di halaman order.
     * @param array $menus
     * @param int|null $parentId
     * @return array
     */
    private function buildMenuHierarchy(array $menus, $parentId = null): array
    {

        $branch = [];
        foreach ($menus as $menu) {
            if ($menu['parent_id'] == $parentId) {
                $children = $this->buildMenuHierarchy($menus, $menu['id']);
                if (!empty($children)) {
                    $menu['children'] = $children;
                }
                $branch[] = $menu;
            }
        }
        return $branch;
    }

    /**
     * Memproses data menu yang diterima dari frontend dan memperbarui database.
     * @param array $menuItems Array menu dari frontend
     * @param int|null $parentId ID parent saat ini
     * @param int $order Start order
     */
    private function processMenuUpdates(array $menuItems, $parentId = null, &$order = 0)
    {
        foreach ($menuItems as $item) {
            $data = [
                'parent_id' => $parentId,
                'order'     => $order++, // Increment order for the same level
            ];
            $this->model->update($item['id'], $data);

            // Jika ada children, panggil rekursif
            if (isset($item['children']) && is_array($item['children'])) {
                $this->processMenuUpdates($item['children'], $item['id'], $order); // Children will start their own order sequence
            }
        }
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $redirect = checkPermission('menus.create');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $rules = [
            'title' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }

        $this->db->transBegin();


        try {
            $data = [
                'title' => htmlspecialchars($this->request->getVar('title'), true),
                'parent_id' => $this->request->getVar('parent_id') ? htmlspecialchars($this->request->getVar('parent_id'), true) : null,
                'icon' => htmlspecialchars($this->request->getVar('icon'), true),
                'permission' => htmlspecialchars($this->request->getVar('permission'), true),
                'route' => htmlspecialchars($this->request->getVar('route'), true),
                'order' => htmlspecialchars($this->request->getVar('order'), true),
            ];

            $this->model->insert($data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to create menu')->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', 'Menu created successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to create menu')->withInput();
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
        $redirect = checkPermission('menus.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $menu = $this->model->find($id);

        if (!$menu) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // return redirect()->to($this->link);
        }

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'menu' => $menu,
            'menus' => $this->model->findAll(),
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
        $redirect = checkPermission('menus.edit');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $menu = $this->model->find($id);

        if (!$menu) {
            return redirect()->to($this->link);
        }

        $rules = [
            'title' => 'required',
        ];

        $input = $this->request->getVar();

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()->withInput();
        }


        $this->db->transBegin();

        try {

            $parent_id = $this->request->getVar('parent_id');

            $data = [
                'title' => htmlspecialchars($this->request->getVar('title'), true),
                'title' => htmlspecialchars($this->request->getVar('title'), true),
                'icon' => htmlspecialchars($this->request->getVar('icon'), true),
                'permission' => htmlspecialchars($this->request->getVar('permission'), true),
                'route' => htmlspecialchars($this->request->getVar('route'), true),
                'order' => htmlspecialchars($this->request->getVar('order'), true),
            ];

            if ($parent_id && $parent_id != "") {
                $data['parent_id'] = htmlspecialchars($parent_id, true);
            }

            $this->model->update($id, $data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to update menu')->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', 'Menu updated successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to update menu ')->withInput();
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
        $redirect = checkPermission('menus.delete');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $menu = $this->model->find($id);

        if (!$menu) {
            return redirect()->to($this->link);
        }

        $this->db->transBegin();

        try {
            $this->model->delete($id);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Failed to delete menu')->withInput();
            }

            $this->db->transCommit();

            $cache = \Config\Services::cache();
            $cache->delete($this->model->cacheKey);

            return redirect()->with('success', 'Menu deleted successfully.')->to($this->link);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to delete menu')->withInput();
        }
    }

    function activate($id = null)
    {
        $menu = $this->model->find($id);

        if (!$menu) {
            return redirect()->to($this->link);
        }

        $this->model->update($id, ['active' => 1]);

        $cache = \Config\Services::cache();
        $cache->delete($this->model->cacheKey);

        return redirect()->with('success', 'Menu activated successfully.')->to($this->link);
    }

    function deactivate($id = null)
    {
        $menu = $this->model->find($id);

        if (!$menu) {
            return redirect()->to($this->link);
        }

        $this->model->update($id, ['active' => 0]);

        $cache = \Config\Services::cache();
        $cache->delete($this->model->cacheKey);

        return redirect()->with('success', 'Menu deactivated successfully.')->to($this->link);
    }
}
