<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedSales extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'sales.access',
                'title' => 'Can access the sales',
                'created_at' => '2026-03-14 14:54:00',
                'updated_at' => '2026-03-14 14:54:00',
            ],
            [
                'name' => 'sales.create',
                'title' => 'Can create sales',
                'created_at' => '2026-03-14 14:54:00',
                'updated_at' => '2026-03-14 14:54:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'sales.access',
                'created_at' => '2026-03-14 14:54:00',
                'updated_at' => '2026-03-14 14:54:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'sales.create',
                'created_at' => '2026-03-14 14:54:00',
                'updated_at' => '2026-03-14 14:54:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);


        $parent_id = null;


        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Sales',
                    'icon' => 'fas fa-box-open',
                    'route' => 'sales',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'sales.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Sales',
                    'icon' => 'fas fa-box-open',
                    'route' => 'sales',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'sales.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);
    }
}
