<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BooksSeeder extends Seeder
{
    public function run()
    {
        $db = db_connect();

        $books = [
            [
                'isbn' => '9786020000001',
                'title' => 'Dasar Pemrograman',
                'author' => 'Tim SiPustaka',
                'publisher' => 'SiPustaka Press',
                'year' => 2023,
                'category' => 'Teknologi',
                'description' => 'Pengantar konsep pemrograman untuk pemula.',
                'cover' => '1765880418_4075fe4f8f0e8b18e518.jpeg', // contoh dari dump :contentReference[oaicite:13]{index=13}
                'stock' => 5,
            ],
            [
                'isbn' => '9786020000002',
                'title' => 'Algoritma & Struktur Data 1',
                'author' => 'Tim SiPustaka',
                'publisher' => 'SiPustaka Press',
                'year' => 2024,
                'category' => 'Teknologi',
                'description' => 'Konsep dasar algoritma dan struktur data.',
                'cover' => '1765880408_b41e998d05d9b20ab13a.jpeg', // :contentReference[oaicite:14]{index=14}
                'stock' => 3,
            ],
            [
                'isbn' => '97860200000021',
                'title' => 'AKu',
                'author' => 'Tim SiPustaka',
                'publisher' => 'SiPustaka Press',
                'year' => 2025,
                'category' => 'Ekonomi Kreatif',
                'description' => '4',
                'cover' => '1765879611_71d6b5bf1b65791af4de.jpeg', // :contentReference[oaicite:15]{index=15}
                'stock' => 1,
            ],
        ];

        foreach ($books as $b) {
            $exists = $db->table('books')->where('isbn', $b['isbn'])->get()->getRowArray();
            if ($exists) continue;

            $db->table('books')->insert([
                ...$b,
                'created_at' => null,
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]);
        }
    }
}
