<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBooksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],

            'isbn' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'author' => ['type' => 'VARCHAR', 'constraint' => 120],
            'publisher' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'year' => ['type' => 'SMALLINT', 'constraint' => 6, 'null' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],

            // sesuai dump: ada cover varchar(255)
            'cover' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // :contentReference[oaicite:4]{index=4}

            'stock' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('isbn');

        $this->forge->createTable('books', true);
    }

    public function down()
    {
        $this->forge->dropTable('books', true);
    }
}
