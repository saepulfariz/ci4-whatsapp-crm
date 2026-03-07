<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedProducts extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'products.access',
                'title' => 'Can access the products',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'name' => 'products.create',
                'title' => 'Can create products',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'name' => 'products.edit',
                'title' => 'Can update products',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'name' => 'products.delete',
                'title' => 'Can delete products',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'products.access',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'products.create',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'products.edit',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'products.delete',
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
                    'title' => 'Products',
                    'icon' => 'fas fa-list',
                    'route' => 'products',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'products.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Products',
                    'icon' => 'fas fa-list',
                    'route' => 'products',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'products.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);

        $data = [
            [
                'category_id' => 1,
                'name' => 'Strawbery chesee',
                'price' => 3500,
                'qty' => 100,
                'image' => 'product.png',
                'description' => '',
                'is_active' => 1,
            ],
            [
                'category_id' => 1,
                'name' => 'Strawbery messes',
                'price' => 3500,
                'qty' => 100,
                'image' => 'product.png',
                'description' => '',
                'is_active' => 1,
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
