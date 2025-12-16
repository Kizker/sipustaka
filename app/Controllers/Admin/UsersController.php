<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class UsersController extends BaseController
{
    private function avatarUrl(?string $avatar): string
    {
        $avatar = $avatar ?? '';
        $path = FCPATH . 'uploads/avatars/' . $avatar;

        if ($avatar && is_file($path)) {
            return '/uploads/avatars/' . $avatar;
        }

        return 'https://via.placeholder.com/80?text=User';
    }

    public function index()
    {
        $db = db_connect();

        $q     = trim((string) $this->request->getGet('q'));
        $role  = trim((string) $this->request->getGet('role')); // admin/user/all
        $active = $this->request->getGet('active'); // 1/0/null

        // Query: users + email(Shield) + group
        $builder = $db->table('users u')
            ->select([
                'u.id', 'u.username', 'u.member_no', 'u.phone', 'u.address', 'u.avatar',
                'u.active', 'u.created_at',
                'ai.secret AS email',
                'agu.group AS role',
            ])
            ->join('auth_identities ai', "ai.user_id = u.id AND ai.type='email_password'", 'left')
            ->join('auth_groups_users agu', "agu.user_id = u.id", 'left');

        if ($q !== '') {
            $builder->groupStart()
                ->like('u.username', $q)
                ->orLike('u.member_no', $q)
                ->orLike('ai.secret', $q)
                ->orLike('u.phone', $q)
                ->groupEnd();
        }

        if ($role !== '' && $role !== 'all') {
            $builder->where('agu.group', $role);
        }

        if ($active === '1' || $active === '0') {
            $builder->where('u.active', (int)$active);
        }

        $users = $builder->orderBy('u.id', 'DESC')->get()->getResultArray();

        // Statistik kecil (optional)
        $totalUsers  = (int) $db->table('users')->countAllResults();
        $activeUsers = (int) $db->table('users')->where('active', 1)->countAllResults();
        $adminUsers  = (int) $db->table('auth_groups_users')->where('group', 'admin')->countAllResults();

        return view('admin/users/index', [
            'title'       => 'Kelola Pengguna',
            'users'       => $users,
            'q'           => $q,
            'role'        => $role ?: 'all',
            'active'      => ($active === null ? 'all' : (string)$active),
            'totalUsers'  => $totalUsers,
            'activeUsers' => $activeUsers,
            'adminUsers'  => $adminUsers,
        ]);
    }

    public function edit(int $id)
    {
        $db = db_connect();

        $user = $db->table('users u')
            ->select([
                'u.id','u.username','u.member_no','u.phone','u.address','u.avatar','u.active',
                'ai.secret AS email',
                'agu.group AS role',
            ])
            ->join('auth_identities ai', "ai.user_id = u.id AND ai.type='email_password'", 'left')
            ->join('auth_groups_users agu', "agu.user_id = u.id", 'left')
            ->where('u.id', $id)
            ->get()->getRowArray();

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        return view('admin/users/edit', [
            'title' => 'Edit Pengguna',
            'user'  => $user,
            'avatarUrl' => $this->avatarUrl($user['avatar'] ?? null),
        ]);
    }

    public function update(int $id)
    {
        $db = db_connect();

        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');

        $rules = [
            'phone'   => 'permit_empty|max_length[25]',
            'address' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Input tidak valid.');
        }

        $data = [
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ];

        // Upload avatar (opsional)
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ok = $this->validate([
                'avatar' => 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]'
            ]);

            if ($ok) {
                $dir = FCPATH . 'uploads/avatars';
                if (!is_dir($dir)) mkdir($dir, 0777, true);

                $name = $file->getRandomName();
                $file->move($dir, $name);
                $data['avatar'] = $name;

                // hapus avatar lama
                if (!empty($user['avatar'])) {
                    $old = $dir . DIRECTORY_SEPARATOR . $user['avatar'];
                    if (is_file($old)) @unlink($old);
                }
            }
        }

        $db->table('users')->where('id', $id)->update($data);

        return redirect()->to('/admin/users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleActive(int $id)
    {
        $db = db_connect();
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');

        $new = ((int)$user['active'] === 1) ? 0 : 1;
        $db->table('users')->where('id', $id)->update(['active' => $new]);

        return redirect()->to('/admin/users')->with('success', 'Status pengguna diperbarui.');
    }

    public function setRole(int $id)
    {
        $db = db_connect();
        $role = (string) $this->request->getPost('role'); // admin/user

        if (!in_array($role, ['admin', 'user'], true)) {
            return redirect()->to('/admin/users')->with('error', 'Role tidak valid.');
        }

        // hapus role lama lalu set role baru (Shield groups)
        $db->table('auth_groups_users')->where('user_id', $id)->delete();
        $db->table('auth_groups_users')->insert([
            'user_id' => $id,
            'group'   => $role,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/users')->with('success', 'Role pengguna berhasil diperbarui.');
    }
}
