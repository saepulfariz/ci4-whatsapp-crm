<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedAuthMenus extends Seeder
{
    public function run()
    {
        $data = [
            [
                'parent_id' => NULL,
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'dashboard',
                'order' => 1,
                'active' => 1,
                'permission' => NULL,
            ],
            [
                'parent_id' => NULL,
                'title' => 'Superadmin',
                'icon' => 'fas fa-list',
                'route' => '#',
                'order' => 2,
                'active' => 1,
                'permission' => null,
            ],
            [
                'parent_id' => 2,
                'title' => 'Users',
                'icon' => 'far fa-circle',
                'route' => 'superadmin/users',
                'order' => 1,
                'active' => 1,
                'permission' => 'users.access',
            ],
            [
                'parent_id' => 2,
                'title' => 'Groups',
                'icon' => 'far fa-circle',
                'route' => 'superadmin/auth-groups',
                'order' => 2,
                'active' => 1,
                'permission' => 'groups.access',
            ],
            [
                'parent_id' => 2,
                'title' => 'Permissions',
                'icon' => 'far fa-circle',
                'route' => 'superadmin/auth-permissions',
                'order' => 3,
                'active' => 1,
                'permission' => 'permissions.access',
            ],
            [
                'parent_id' => 2,
                'title' => 'Permissions Groups',
                'icon' => 'far fa-circle',
                'route' => 'superadmin/auth-permissions-groups',
                'order' => 4,
                'active' => 1,
                'permission' => 'permission-group.access',
            ],
            [
                'parent_id' => 2,
                'title' => 'Groups Users',
                'icon' => 'far fa-circle',
                'route' => 'superadmin/auth-groups-users',
                'order' => 5,
                'active' => 1,
                'permission' => 'group-user.access',
            ],
            [
                'parent_id' => 2,
                'title' => 'Permissions Users',
                'icon' => 'far fa-circle',
                'route' => 'superadmin/auth-permissions-users',
                'order' => 6,
                'active' => 1,
                'permission' => 'permission-user.access',
            ],
            [
                'parent_id' => 2,
                'title' => 'Menus',
                'icon' => 'far fa-circle',
                'route' => 'superadmin/auth-menus',
                'order' => 7,
                'active' => 1,
                'permission' => 'menus.access',
            ],
        ];

        $this->db->table('auth_menus')->insertBatch($data);
    }
}
