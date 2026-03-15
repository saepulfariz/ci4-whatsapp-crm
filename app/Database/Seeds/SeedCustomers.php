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

            [
                'name' => 'customer-groups.access',
                'title' => 'Can access the customer-groups',
                'created_at' => '2026-03-12 04:41:00',
                'updated_at' => '2026-03-12 04:41:00',
            ],
            [
                'name' => 'customer-groups.create',
                'title' => 'Can create customer-groups',
                'created_at' => '2026-03-12 04:41:00',
                'updated_at' => '2026-03-12 04:41:00',
            ],
            [
                'name' => 'customer-groups.edit',
                'title' => 'Can update customer-groups',
                'created_at' => '2026-03-12 04:41:00',
                'updated_at' => '2026-03-12 04:41:00',
            ],
            [
                'name' => 'customer-groups.delete',
                'title' => 'Can delete customer-groups',
                'created_at' => '2026-03-12 04:41:00',
                'updated_at' => '2026-03-12 04:41:00',
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

            [
                'group_id' => 1,
                'permission' => 'customer-groups.access',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'customer-groups.create',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'customer-groups.edit',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'customer-groups.delete',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);

        $parent_id = $this->db->table('auth_menus')->limit(1)->where('title', 'Master Data')->get()->getRowArray()['id'] ?? null;


        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Customers',
                    'icon' => 'fas fa-address-book',
                    'route' => 'customers',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'customers.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Customers',
                    'icon' => 'fas fa-address-book',
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
