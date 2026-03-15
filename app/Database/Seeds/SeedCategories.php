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

        $parent_meenu = [
            'parent_id' => NULL,
            'title' => 'Master Data',
            'icon' => 'fas fa-list',
            'route' => '#',
            'order' => 3,
            'active' => 1,
            'permission' => null,
        ];

        $this->db->table('auth_menus')->insert($parent_meenu);
        $parent_id = $this->db->table('auth_menus')->limit(1)->where('title', 'Master Data')->get()->getRowArray()['id'] ?? null;

        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => $parent_id,
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
                    'parent_id' => $parent_id,
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
                'code' => 'CAT-001',
                'name' => 'Regular Donuts',
                'description' => 'Classic donut varieties',
                'status' => 'Active',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'code' => 'CAT-002',
                'name' => 'Premium Donuts',
                'description' => 'Premium donut selection',
                'status' => 'Active',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],

        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
