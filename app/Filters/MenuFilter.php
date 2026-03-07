<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Superadmin\authMenuModel;

class MenuFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authMenuModel = new authMenuModel();
        $user = service('auth')->user();

        $cache = \Config\Services::cache();
        if (!$allMenus = $cache->get($authMenuModel->cacheKey)) {
            $allMenus = $authMenuModel->where('active', 1)->orderBy('order', 'ASC')->findAll();
            $cache->save($authMenuModel->cacheKey, $allMenus, CACHE_TTL); // Cache for 5 minutes
        } else {
            $allMenus = $cache->get($authMenuModel->cacheKey);
        }
        // $allMenus = $authMenuModel->where('active', 1)->orderBy('order', 'ASC')->findAll();

        $allowedMenus = $this->filterMenusByPermission($allMenus, $user);

        $navMenus = $this->buildMenuHierarchy($allowedMenus);

        // Ambil dari Constants.php
        $lengthMenuValues = array_keys(SHOW_OPTIONS);
        $lengthMenuLabels = array_values(SHOW_OPTIONS);

        $array_show_options = [
            'values' => $lengthMenuValues,
            'labels' => $lengthMenuLabels
        ];

        // Simpan menu yang sudah difilter dan hirarkis ke service atau view variable
        // Misalnya, Anda bisa menyimpannya di config atau service()
        service('renderer')->setData(['sidebarMenus' => $navMenus]);
        service('renderer')->setData(['show_options' => $array_show_options]);

        // Atau: \Config\Services::menu()->setMenus($navMenus); (Jika Anda membuat service menu sendiri)
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }

    /**
     * Memfilter menu berdasarkan permission pengguna.
     * @param array $menus
     * @param object $user Shield user object
     * @return array
     */
    private function filterMenusByPermission(array $menus, $user): array
    {
        $filtered = [];
        foreach ($menus as $menu) {
            // Jika menu tidak punya permission spesifik, biarkan lolos untuk sementara
            // Filter akhir akan dilakukan saat membangun hirarki berdasarkan parent
            $permissions = $menu['permission'];
            $permissions = explode('|', $permissions);
            $can_access = false;
            if (count($permissions) > 1) {
                foreach ($permissions as $permission) {
                    if ($user && $user->can($permission)) {
                        $can_access = true;
                    } else {
                        $can_access = false;
                    }
                }
            } else {
                if (empty($menu['permission']) || ($user && $user->can($permissions[0]))) {
                    $can_access = true;
                } else {
                    $can_access = false;
                }
            }

            // ($user && $user->can($menu['permission']))
            if (empty($menu['permission']) || $can_access) {
                $filtered[] = $menu;
            }
        }
        return $filtered;
    }

    /**
     * Membangun struktur menu hirarkis.
     * @param array $menus Filtered menu list
     * @param int|null $parentId Parent ID saat ini
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
}
