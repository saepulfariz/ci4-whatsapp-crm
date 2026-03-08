<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedCategories extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'categories.access',
                'title' => 'Can access the categories',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'name' => 'categories.create',
                'title' => 'Can create categories',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'name' => 'categories.edit',
                'title' => 'Can update categories',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'name' => 'categories.delete',
                'title' => 'Can delete categories',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'categories.access',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'categories.create',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'categories.edit',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'categories.delete',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);

        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Categories',
                    'icon' => 'fas fa-tags',
                    'route' => 'categories',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'categories.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Categories',
                    'icon' => 'fas fa-tags',
                    'route' => 'categories',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'categories.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);

        $data = [
            [
                'name' => 'Donat',
            ],
            [
                'name' => 'Kueh',
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
