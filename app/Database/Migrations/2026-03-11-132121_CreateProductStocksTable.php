<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductStocksTable extends Migration
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
            'product_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default' => 'Stock In'
            ],
            'qty' => [
                'type'       => 'FLOAT',
                'default' => 0
            ],
            'current_stock' => [
                'type'       => 'FLOAT',
                'default' => 0
            ],
            'prev_stock' => [
                'type'       => 'FLOAT',
                'default' => 0
            ],
            'note' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'date' => [
                'type'           => 'DATETIME',
                'null' => true,
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
        $this->forge->addForeignKey('product_id', 'products', 'id');
        $this->forge->createTable('product_stocks');
    }

    public function down()
    {
        $this->forge->dropTable('product_stocks');
    }
}
