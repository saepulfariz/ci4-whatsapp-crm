<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedAutoReplies extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'auto-replies.access',
                'title' => 'Can access the auto replies',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
            [
                'name' => 'auto-replies.create',
                'title' => 'Can create auto replies',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
            [
                'name' => 'auto-replies.edit',
                'title' => 'Can update auto replies',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
            [
                'name' => 'auto-replies.delete',
                'title' => 'Can delete auto replies',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'auto-replies.access',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'auto-replies.create',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'auto-replies.edit',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'auto-replies.delete',
                'created_at' => '2026-03-08 09:23:00',
                'updated_at' => '2026-03-08 09:23:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);

        if (ENVIRONMENT === 'development') {
            // mode dev
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Auto Replies',
                    'icon' => 'fas fa-comment-dots',
                    'route' => 'auto-replies',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'auto-replies.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Auto Replies',
                    'icon' => 'fas fa-comment-dots',
                    'route' => 'auto-replies',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'auto-replies.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);

        $data = [
            [
                'keyword' => 'Hai',
                'content' => 'Halo',
                'is_exact_match' => 1,
            ],
            [
                'keyword' => 'Halo',
                'content' => 'Hai',
                'is_exact_match' => 1,
            ],
        ];

        $this->db->table('auto_replies')->insertBatch($data);
    }
}
