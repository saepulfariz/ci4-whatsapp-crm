<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedCustomers extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'customers.access',
                'title' => 'Can access the customers',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'name' => 'customers.create',
                'title' => 'Can create customers',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'name' => 'customers.edit',
                'title' => 'Can update customers',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'name' => 'customers.delete',
                'title' => 'Can delete customers',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'customers.access',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'customers.create',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'customers.edit',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'customers.delete',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);

        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Customers',
                    'icon' => 'fas fa-list',
                    'route' => 'customers',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'customers.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Customers',
                    'icon' => 'fas fa-list',
                    'route' => 'customers',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'customers.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);
    }
}
