<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedTransactions extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'transactions.access',
                'title' => 'Can access the transactions',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'name' => 'transactions.create',
                'title' => 'Can create transactions',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'name' => 'transactions.edit',
                'title' => 'Can update transactions',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'name' => 'transactions.delete',
                'title' => 'Can delete transactions',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'transactions.access',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'transactions.create',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'transactions.edit',
                'created_at' => '2026-03-07 16:58:00',
                'updated_at' => '2026-03-07 16:58:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'transactions.delete',
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
                    'title' => 'Transactions',
                    'icon' => 'fas fa-list',
                    'route' => 'transactions',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'transactions.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Transactions',
                    'icon' => 'fas fa-list',
                    'route' => 'transactions',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'transactions.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);
    }
}
