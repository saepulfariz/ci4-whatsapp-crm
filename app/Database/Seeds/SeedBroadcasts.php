<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedBroadcasts extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'broadcasts.access',
                'title' => 'Can access the broadcasts',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'name' => 'broadcasts.create',
                'title' => 'Can create broadcasts',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'name' => 'broadcasts.edit',
                'title' => 'Can update broadcasts',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'name' => 'broadcasts.delete',
                'title' => 'Can delete broadcasts',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);

        $data = [
            [
                'group_id' => 1,
                'permission' => 'broadcasts.access',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'broadcasts.create',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'broadcasts.edit',
                'created_at' => '2026-03-07 13:50:00',
                'updated_at' => '2026-03-07 13:50:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'broadcasts.delete',
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
                    'title' => 'Broadcasts',
                    'icon' => 'fas fa-bullhorn',
                    'route' => 'broadcasts',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'broadcasts.access',
                ],
            ];
        } else {
            $data = [
                [
                    'parent_id' => NULL,
                    'title' => 'Broadcasts',
                    'icon' => 'fas fa-bullhorn',
                    'route' => 'broadcasts',
                    'order' => 5,
                    'active' => 1,
                    'permission' => 'broadcasts.access',
                ],
            ];
        }

        $this->db->table('auth_menus')->insertBatch($data);

        $data = [
            [
                'title' => 'Daily Stock Update',
                'content' => '
Hi, Pak/Ibu {client_name},
Update stok product hari ini:

No | Nama | Stok | Harga
{products}{no}. {name} - {stock} - {price}
{/products}

Pengantaran tesedia jam {delivery_time}
Silahkan balas jika ingin order!

Terima kasih,
{company_name}
                ',
            ],
        ];

        $this->db->table('broadcasts')->insertBatch($data);

        $data = [
            [
                'broadcast_id' => 1,
                'name' => 'client_name',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'products',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'no',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'name',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'qty',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'hold_qty',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'stock',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'price',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'description',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'delivery_time',
            ],
            [
                'broadcast_id' => 1,
                'name' => 'company_name',
            ],
        ];

        $this->db->table('broadcast_variables')->insertBatch($data);
    }
}
