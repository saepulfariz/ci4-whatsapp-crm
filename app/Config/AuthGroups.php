<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Complete control of the site.',
        ],
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Day to day administrators of the site.',
        ],
        'developer' => [
            'title'       => 'Developer',
            'description' => 'Site programmers.',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'General users of the site. Often customers.',
        ],
        'beta' => [
            'title'       => 'Beta User',
            'description' => 'Has access to beta-level features.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'        => 'Can access the sites admin area',
        'admin.settings'      => 'Can access the main site settings',
        'users.manage-admins' => 'Can manage other admins',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'beta.access'         => 'Can access beta-level features',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.*',
            'users.*',
            'beta.*',
        ],
        'admin' => [
            'admin.access',
            'users.create',
            'users.edit',
            'users.delete',
            'beta.access',
        ],
        'developer' => [
            'admin.access',
            'admin.settings',
            'users.create',
            'users.edit',
            'beta.access',
        ],
        'user' => [],
        'beta' => [
            'beta.access',
        ],
    ];

    private $auth_groups = [];

    public function __construct()
    {
        parent::__construct();
        $this->setGroups();
        $this->setPermissions();
        $this->setMatrix();
    }

    function setGroups()
    {
        $db = db_connect();
        $cache = \Config\Services::cache();
        $cacheKey = 'auth_groups';
        if (!$cache->get($cacheKey)) {
            $data = $db->table('auth_groups')->get()->getResultArray();
            $cache->save($cacheKey, $data, CACHE_TTL); // Cache for 60 minutes
        } else {
            $data = $cache->get($cacheKey);
        }

        $this->auth_groups = $data;
        $result = [];
        foreach ($data as $d) {
            $result[$d['name']] = [
                'title' => $d['title'],
                'description' => $d['description'],
            ];
        }
        $this->groups = $result;

        return $result;
    }

    function setPermissions()
    {
        $db = db_connect();
        $cache = \Config\Services::cache();
        $cacheKey = 'auth_permissions';
        if (!$cache->get($cacheKey)) {
            $data = $db->table('auth_permissions')->get()->getResultArray();
            $cache->save($cacheKey, $data, CACHE_TTL);
        } else {
            $data = $cache->get($cacheKey);
        }

        $result = [];
        foreach ($data as $d) {
            $result[$d['name']] = $d['title'];
        }
        $this->permissions = $result;
        return $result;
    }

    function setMatrix()
    {
        $db = db_connect();
        $data = $this->auth_groups;
        $result = [];
        $cache = \Config\Services::cache();
        $cacheKey = 'auth_permissions_groups';
        if (!$cache->get($cacheKey)) {
            $data_permissions = $db->table('auth_permissions_groups')->get()->getResultArray();
            $cache->save($cacheKey, $data_permissions, CACHE_TTL);
        } else {
            $data_permissions = $cache->get($cacheKey);
        }
        foreach ($data as $d) {
            foreach ($data_permissions as $dp) {
                if ($dp['group_id'] == $d['id']) {
                    $result[$d['name']][] = $dp['permission'];
                }
            }
            // $data_permissions = $db->table('auth_permissions_groups')->where('group_id', $d['id'])->get()->getResultArray();
            // $permissions = [];
            // foreach ($data_permissions as $dp) {
            //     $permissions[] = $dp['permission'];
            // }
            // $result[$d['name']] = $permissions;
        }
        $this->matrix = $result;
        return $result;
    }
}
