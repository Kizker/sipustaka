<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $db = db_connect();

        $users = [
            [
                'username' => 'admin',
                'email'    => 'admin@sipustaka.test',  // :contentReference[oaicite:9]{index=9}
                'password' => 'admin12345',
                'group'    => 'admin',                  // :contentReference[oaicite:10]{index=10}
                'phone'    => '081234567890',
                'address'  => 'Blok A',
                'avatar'   => null,
            ],
            [
                'username' => 'andri',
                'email'    => 'user@sipustaka.test',   // :contentReference[oaicite:11]{index=11}
                'password' => 'user12345',
                'group'    => 'user',                   // :contentReference[oaicite:12]{index=12}
                'phone'    => '088747378869',
                'address'  => 'Blok A',
                'avatar'   => null,
            ],
        ];

        foreach ($users as $u) {
            $existing = $db->table('users')->where('username', $u['username'])->get()->getRowArray();
            if ($existing) {
                continue;
            }

            $now = date('Y-m-d H:i:s');

            // insert ke users
            $db->table('users')->insert([
                'username'    => $u['username'],
                'phone'       => $u['phone'],
                'address'     => $u['address'],
                'avatar'      => $u['avatar'],
                'active'      => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            $userId = (int) $db->insertID(); // trigger akan isi member_no otomatis

            // insert identity Shield: email_password
            $db->table('auth_identities')->insert([
                'user_id'     => $userId,
                'type'        => 'email_password',
                'name'        => null,
                'secret'      => $u['email'],
                'secret2'     => password_hash($u['password'], PASSWORD_DEFAULT),
                'expires'     => null,
                'extra'       => null,
                'force_reset' => 0,
                'last_used_at'=> null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // set group
            $db->table('auth_groups_users')->insert([
                'user_id'    => $userId,
                'group'      => $u['group'],
                'created_at' => $now,
            ]);
        }
    }
}
