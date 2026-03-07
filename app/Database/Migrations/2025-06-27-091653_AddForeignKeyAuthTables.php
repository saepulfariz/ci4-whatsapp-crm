<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyAuthTables extends Migration
{
    public function up()
    {
        $platform = $this->db->DBDriver;
        switch ($platform) {
            case 'MySQLi':
                // $this->db->query('ALTER TABLE auth_groups_users ADD CONSTRAINT auth_groups_users_group_foreign FOREIGN KEY (group) REFERENCES auth_groups(name) ON DELETE CASCADE ON UPDATE CASCADE');
                $this->db->query('ALTER TABLE `auth_groups_users` ADD CONSTRAINT `auth_groups_users_group_foreign` FOREIGN KEY (`group`) REFERENCES `auth_groups`(`name`) ON DELETE CASCADE ON UPDATE RESTRICT;');

                // if permission value users.* don't use foreign key.
                // $this->db->query('ALTER TABLE `auth_permissions_users` ADD CONSTRAINT `auth_permissions_users_permission_foreign` FOREIGN KEY (`permission`) REFERENCES `auth_permissions`(`name`) ON DELETE CASCADE ON UPDATE RESTRICT;');
                break;

            case 'SQLite3':
                // SQLite sangat terbatas dengan ALTER TABLE, tidak bisa ADD CONSTRAINT langsung
                // Biasanya solusi: buat ulang tabel (tidak praktis di migrasi modular)
                echo "Manual migration needed for SQLite.\n";
                break;

            case 'SQLSRV':
                $this->db->query('ALTER TABLE [auth_groups_users] ADD CONSTRAINT [auth_groups_users_group_foreign] FOREIGN KEY ([group]) REFERENCES [auth_groups]([name])');

                // if permission value users.* don't use foreign key.
                // $this->db->query('ALTER TABLE [auth_permissions_users] ADD CONSTRAINT [auth_permissions_users_permission_foreign] FOREIGN KEY ([permission]) REFERENCES [auth_permissions]([name])');
                break;

            default:
                throw new \Exception("Unsupported DB driver: $platform");
        }
    }

    public function down()
    {
        $platform = $this->db->DBDriver;


        switch ($platform) {
            case 'MySQLi':
                $this->db->query('ALTER TABLE auth_groups_users DROP FOREIGN KEY auth_groups_users_group_foreign');
                // $this->db->query('ALTER TABLE auth_permissions_users DROP FOREIGN KEY auth_permissions_users_permission_foreign');
                break;

            case 'SQLSRV':
                $this->db->query('ALTER TABLE auth_groups_users DROP CONSTRAINT auth_groups_users_group_foreign');
                // $this->db->query('ALTER TABLE auth_permissions_users DROP CONSTRAINT auth_permissions_users_permission_foreign');
                break;

            case 'SQLite3':
                echo "Manual migration needed for SQLite.\n";
                break;

            default:
                throw new \Exception("Unsupported DB driver: $platform");
        }
    }
}
