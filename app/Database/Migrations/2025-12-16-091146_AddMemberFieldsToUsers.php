<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoansTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],

            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'book_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],

            'borrowed_at' => ['type' => 'DATETIME'],
            'due_at' => ['type' => 'DATETIME', 'null' => true],
            'returned_at' => ['type' => 'DATETIME', 'null' => true],

            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'borrowed'], // :contentReference[oaicite:5]{index=5}

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('book_id');

        // FK sesuai dump
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('book_id', 'books', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('loans', true);
    }

    public function down()
    {
        $this->forge->dropTable('loans', true);
    }
}
