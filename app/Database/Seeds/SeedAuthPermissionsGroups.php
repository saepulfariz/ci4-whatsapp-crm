<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedAuthPermissionsGroups extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'superadmin',
                'title' => 'Super Admin',
                'description' => 'Complete control of the site.',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'admin',
                'title' => 'Admin',
                'description' => 'Day to day administrators of the site.',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'developer',
                'title' => 'Developer',
                'description' => 'Site programmers.',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'user',
                'title' => 'User',
                'description' => 'General users of the site. Often customers.',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'beta',
                'title' => 'Beta User',
                'description' => 'Has access to beta-level features.',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
        ];

        $this->db->table('auth_groups')->insertBatch($data);


        $data = [
            [
                'name' => 'admin.access',
                'title' => 'Can access the sites admin area',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'admin.settings',
                'title' => 'Can access the main site settings',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'users.manage-admins',
                'title' => 'Can manage other admins',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'users.create',
                'title' => 'Can create new non-admin users',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'users.edit',
                'title' => 'Can edit existing non-admin users',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'users.delete',
                'title' => 'Can delete existing non-admin users',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'beta.access',
                'title' => 'Can access beta-level features',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'name' => 'users.access',
                'title' => 'Can access the users area',
                'created_at' => '2025-07-11 18:48:00',
                'updated_at' => '2025-07-11 18:48:00',
            ],

            [
                'name' => 'groups.access',
                'title' => 'Can access the groups area',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'groups.create',
                'title' => 'Can create new groups',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'groups.edit',
                'title' => 'Can edit existing groups',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'groups.delete',
                'title' => 'Can delete existing groups',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],

            [
                'name' => 'permissions.access',
                'title' => 'Can access the permissions area',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'permissions.create',
                'title' => 'Can create new permissions',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'permissions.edit',
                'title' => 'Can edit existing permissions',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'permissions.delete',
                'title' => 'Can delete existing permissions',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],

            [
                'name' => 'permission-group.access',
                'title' => 'Can access the permission-group area',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'permission-group.create',
                'title' => 'Can create new permission-group',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'permission-group.edit',
                'title' => 'Can edit existing permission-group',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'permission-group.delete',
                'title' => 'Can delete existing permission-group',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],

            [
                'name' => 'group-user.access',
                'title' => 'Can access the group-user area',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'group-user.create',
                'title' => 'Can create new group-user',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'group-user.edit',
                'title' => 'Can edit existing group-user',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'group-user.delete',
                'title' => 'Can delete existing group-user',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],

            [
                'name' => 'permission-user.access',
                'title' => 'Can access the permission-user area',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'permission-user.create',
                'title' => 'Can create new permission-user',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'permission-user.edit',
                'title' => 'Can edit existing permission-user',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'permission-user.delete',
                'title' => 'Can delete existing permission-user',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],


            [
                'name' => 'menus.access',
                'title' => 'Can access the auth-menus area',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'menus.create',
                'title' => 'Can create new auth-menus',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'menus.edit',
                'title' => 'Can edit existing auth-menus',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'name' => 'menus.delete',
                'title' => 'Can delete existing auth-menus',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
        ];

        $this->db->table('auth_permissions')->insertBatch($data);


        $data = [
            // [
            //     'group_id' => 1,
            //     'permission' => 'admin.*',
            //     'created_at' => '2025-06-27 17:13:00',
            //     'updated_at' => '2025-06-27 17:13:00',
            // ],
            // [
            //     'group_id' => 1,
            //     'permission' => 'users.*',
            //     'created_at' => '2025-06-27 17:13:00',
            //     'updated_at' => '2025-06-27 17:13:00',
            // ],
            // [
            //     'group_id' => 1,
            //     'permission' => 'beta.*',
            //     'created_at' => '2025-06-27 17:13:00',
            //     'updated_at' => '2025-06-27 17:13:00',
            // ],
            [
                'group_id' => 2,
                'permission' => 'admin.access',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 2,
                'permission' => 'users.access',
                'created_at' => '2025-07-11 18:48:00',
                'updated_at' => '2025-07-11 18:48:00',
            ],
            [
                'group_id' => 2,
                'permission' => 'users.create',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 2,
                'permission' => 'users.edit',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 2,
                'permission' => 'users.delete',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 2,
                'permission' => 'beta.access',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 3,
                'permission' => 'admin.access',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 3,
                'permission' => 'admin.settings',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 3,
                'permission' => 'users.create',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 3,
                'permission' => 'users.edit',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 3,
                'permission' => 'beta.access',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],
            [
                'group_id' => 5,
                'permission' => 'beta.access',
                'created_at' => '2025-06-27 17:13:00',
                'updated_at' => '2025-06-27 17:13:00',
            ],


            [
                'group_id' => 1,
                'permission' => 'users.access',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'users.create',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'users.edit',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'users.delete',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],

            [
                'group_id' => 1,
                'permission' => 'groups.access',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'groups.create',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'groups.edit',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'groups.delete',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],

            [
                'group_id' => 1,
                'permission' => 'permissions.access',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'permissions.create',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'permissions.edit',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'permissions.delete',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],


            [
                'group_id' => 1,
                'permission' => 'permission-group.access',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'permission-group.create',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'permission-group.edit',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'permission-group.delete',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],

            [
                'group_id' => 1,
                'permission' => 'group-user.access',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'group-user.create',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'group-user.edit',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'group-user.delete',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],

            [
                'group_id' => 1,
                'permission' => 'permission-user.access',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'permission-user.create',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'permission-user.edit',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'permission-user.delete',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],

            [
                'group_id' => 1,
                'permission' => 'menus.access',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'menus.create',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'menus.edit',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
            [
                'group_id' => 1,
                'permission' => 'menus.delete',
                'created_at' => '2025-09-14 16:01:00',
                'updated_at' => '2025-09-14 16:01:00',
            ],
        ];

        $this->db->table('auth_permissions_groups')->insertBatch($data);
    }
}
