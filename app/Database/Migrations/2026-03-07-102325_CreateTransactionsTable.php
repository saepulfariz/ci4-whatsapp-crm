<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
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
            'customer_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default' => 'pending'
            ],
            'order_date' => [
                'type'       => 'DATETIME',
                'null' => true
            ],
            'schedule_date' => [
                'type'       => 'DATETIME',
                'null' => true
            ],
            'delivery_date' => [
                'type'       => 'DATETIME',
                'null' => true
            ],
            'subtotal_price' => [
                'type'       => 'FLOAT',
                'default' => 0
            ],
            'discount_total' => [
                'type'       => 'FLOAT',
                'default' => 0
            ],
            'tax_total' => [
                'type'       => 'FLOAT',
                'default' => 0
            ],
            'total_amount' => [
                'type'       => 'FLOAT',
                'default' => 0
            ],
            'paid_amount' => [
                'type'       => 'FLOAT',
                'default' => 0
            ],
            'refund_amount' => [
                'type'       => 'FLOAT',
                'default' => 0
            ],
            'payment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default' => 'unpaid'
            ],
            'note' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => true
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
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
