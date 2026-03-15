<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedReports extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'reports.access',
                'title' => 'Can access the reports',
                'created_at' => '2026-03-15 12:48:00',
                'updated_at' => '2026-03-15 12:48:00',
            ],
            [
                'name' => 'reports.sales',
                'title' => 'Can access the sales report',
                'created_at' => '2026-03-15 12:48:00',
                'updated_at' => '2026-03-15 12:48:00',
            ],
            [
                'name' => 'reports.stock',
                'title' => 'Can access the stock report',
                'created_at' => '2026-03-15 12:48:00',
                'updated_at' => '2026-03-15 12:48:00',
            ],
            [
                'name' => 'reports.profit',
                'title' => 'Can access the profit report',
                'created_at' => '2026-03-15 12:48:00',
                'updated_at' => '2026-03-15 12:48:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'reports.access',
                'created_at' => '2026-03-15 12:49:00',
                'updated_at' => '2026-03-15 12:49:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'reports.sales',
                'created_at' => '2026-03-15 12:49:00',
                'updated_at' => '2026-03-15 12:49:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'reports.stock',
                'created_at' => '2026-03-15 12:49:00',
                'updated_at' => '2026-03-15 12:49:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'reports.profit',
                'created_at' => '2026-03-15 12:49:00',
                'updated_at' => '2026-03-15 12:49:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);

        $parent_id = null;


        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Reports',
                    'icon' => 'fas fa-box-open',
                    'route' => 'reports',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'reports.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Reports',
                    'icon' => 'fas fa-box-open',
                    'route' => 'reports',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'reports.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);
    }
}
