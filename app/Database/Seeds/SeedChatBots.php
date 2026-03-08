<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedChatBots extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'chat-bots.access',
                'title' => 'Can access the chat bots',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'chat-bots.access',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);

        if (ENVIRONMENT === 'development') {
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Chat Bots',
                    'icon' => 'fas fa-robot',
                    'route' => 'chat-bots',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'chat-bots.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Chat Bots',
                    'icon' => 'fas fa-robot',
                    'route' => 'chat-bots',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'chat-bots.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);
    }
}
