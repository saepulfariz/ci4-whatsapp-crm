<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedShareBroadcasts extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'share-broadcasts.access',
                'title' => 'Can access the share broadcasts',
                'created_at' => '2026-03-08 06:32:00',
                'updated_at' => '2026-03-08 06:32:00',
            ],
            [
                'name' => 'share-broadcasts.create',
                'title' => 'Can create share broadcasts',
                'created_at' => '2026-03-08 06:32:00',
                'updated_at' => '2026-03-08 06:32:00',
            ],
            [
                'name' => 'share-broadcasts.reshare',
                'title' => 'Can reshare broadcasts',
                'created_at' => '2026-03-08 06:32:00',
                'updated_at' => '2026-03-08 06:32:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'share-broadcasts.access',
                'created_at' => '2026-03-08 06:32:00',
                'updated_at' => '2026-03-08 06:32:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'share-broadcasts.create',
                'created_at' => '2026-03-08 06:32:00',
                'updated_at' => '2026-03-08 06:32:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'share-broadcasts.reshare',
                'created_at' => '2026-03-08 06:32:00',
                'updated_at' => '2026-03-08 06:32:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);

        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Share Broadcasts',
                    'icon' => 'fas fa-share-alt',
                    'route' => 'share-broadcasts',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'share-broadcasts.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Share Broadcasts',
                    'icon' => 'fas fa-share-alt',
                    'route' => 'share-broadcasts',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'share-broadcasts.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);
    }
}
