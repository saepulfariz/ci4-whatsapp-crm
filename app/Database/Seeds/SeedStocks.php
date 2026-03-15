<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedStocks extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'stocks.access',
                'title' => 'Can access the stocks',
                'created_at' => '2026-03-11 22:07:00',
                'updated_at' => '2026-03-11 22:07:00',
            ],
            [
                'name' => 'stocks.create',
                'title' => 'Can create stocks',
                'created_at' => '2026-03-11 22:07:00',
                'updated_at' => '2026-03-11 22:07:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'stocks.access',
                'created_at' => '2026-03-11 22:07:00',
                'updated_at' => '2026-03-11 22:07:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'stocks.create',
                'created_at' => '2026-03-11 22:07:00',
                'updated_at' => '2026-03-11 22:07:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);

        $parent_id = $this->db->table('auth_menus')->limit(1)->where('title', 'Master Data')->get()->getRowArray()['id'] ?? null;


        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Stocks',
                    'icon' => 'fas fa-box-open',
                    'route' => 'stocks',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'stocks.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Stocks',
                    'icon' => 'fas fa-box-open',
                    'route' => 'stocks',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'stocks.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);

        $data = [
            [
                'product_id' => 1,
                'qty' => 50,
                'prev_stock' => 0,
                'current_stock' => 50,
                'note' => '',
                'type' => 'Stock In',
            ],
            [
                'product_id' => 1,
                'qty' => 100,
                'prev_stock' => 0,
                'current_stock' => 100,
                'note' => '',
                'type' => 'Stock In',
            ],
        ];

        $this->db->table('product_stocks')->insertBatch($data);
    }
}
