<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedWhatsappBroadcasts extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'whatsapp-broadcasts.access',
                'title' => 'Can access the whatsapp-broadcasts',
                'created_at' => '2026-03-15 17:20:00',
                'updated_at' => '2026-03-15 17:20:00',
            ],
            [
                'name' => 'whatsapp-broadcasts.create',
                'title' => 'Can create whatsapp-broadcasts',
                'created_at' => '2026-03-15 17:20:00',
                'updated_at' => '2026-03-15 17:20:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'whatsapp-broadcasts.access',
                'created_at' => '2026-03-15 17:20:00',
                'updated_at' => '2026-03-15 17:20:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'whatsapp-broadcasts.create',
                'created_at' => '2026-03-15 17:20:00',
                'updated_at' => '2026-03-15 17:20:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);

        $parent_id = null;


        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Whatsapp Broadcasts',
                    'icon' => 'fas fa-share-alt',
                    'route' => 'whatsapp-broadcasts',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'whatsapp-broadcasts.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => $parent_id,
                    'title' => 'Whatsapp Broadcasts',
                    'icon' => 'fas fa-share-alt',
                    'route' => 'whatsapp-broadcasts',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'whatsapp-broadcasts.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);
    }
}
