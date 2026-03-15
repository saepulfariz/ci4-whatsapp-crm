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

        $parent_id = $this->db->table('auth_menus')->limit(1)->where('title', 'Master Data')->get()->getRowArray()['id'] ?? null;


        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Products',
                    'icon' => 'fas fa-box-open',
                    'route' => 'products',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'products.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Products',
                    'icon' => 'fas fa-box-open',
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
                'code' => 'STRW-001',
                'name' => 'Strawbery chesee',
                'price' => 3500,
                'cogs' => 2500,
                'qty' => 50,
                'min_qty' => 10,
                'image' => 'product.png',
                'description' => '',
                'status' => 'Active',
            ],
            [
                'category_id' => 1,
                'code' => 'STRW-002',
                'name' => 'Strawbery messes',
                'price' => 3500,
                'cogs' => 2500,
                'qty' => 100,
                'min_qty' => 10,
                'image' => 'product.png',
                'description' => '',
                'status' => 'Active',
            ],
        ];

        $this->db->table('products')->insertBatch($data);

        // insert to product_stocks
        $data = [
            [
                'product_id' => 1,
                'type' => 'Stock In',
                'qty' => 50,
                'prev_stock' => 50,
                'current_stock' => 50,
                'note' => 'Initial Stock',
                'cid' => 1,
            ],
            [
                'product_id' => 2,
                'type' => 'Stock In',
                'qty' => 100,
                'prev_stock' => 100,
                'current_stock' => 100,
                'note' => 'Initial Stock',
                'cid' => 1,
            ],
        ];
        $this->db->table('product_stocks')->insertBatch($data);
    }
}
