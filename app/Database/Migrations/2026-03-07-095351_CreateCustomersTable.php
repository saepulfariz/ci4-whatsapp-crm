<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'group_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'address' => [
                'type'       => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default' => 'Active'
            ],
            'cid' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null' => true,
            ],
            'uid' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null' => true,
            ],
            'did' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null' => true,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('group_id', 'groups', 'id');
        $this->forge->createTable('customers');
    }

    public function down()
    {
        $this->forge->dropTable('customers');
    }
}
