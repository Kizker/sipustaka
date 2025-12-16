<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LoansSeeder extends Seeder
{
    public function run()
    {
        $db = db_connect();

        // ambil user "andri"
        $user = $db->table('users')->where('username', 'andri')->get()->getRowArray();
        if (!$user) return;

        // ambil buku isbn
        $book = $db->table('books')->where('isbn', '9786020000002')->get()->getRowArray();
        if (!$book) return;

        // cegah duplikat: kalau sudah pernah pinjam buku tsb, skip
        $exists = $db->table('loans')
            ->where('user_id', $user['id'])
            ->where('book_id', $book['id'])
            ->get()->getRowArray();

        if ($exists) return;

        $borrowedAt = date('Y-m-d H:i:s');
        $dueAt      = date('Y-m-d H:i:s', strtotime('+7 days'));

        $db->table('loans')->insert([
            'user_id'     => (int)$user['id'],
            'book_id'     => (int)$book['id'],
            'borrowed_at' => $borrowedAt,
            'due_at'      => $dueAt,
            'returned_at' => null,
            'status'      => 'borrowed', // :contentReference[oaicite:16]{index=16}
            'created_at'  => $borrowedAt,
            'updated_at'  => $borrowedAt,
        ]);
    }
}
