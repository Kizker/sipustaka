<?php

namespace App\Controllers;

use App\Models\UserProfileModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $user = auth()->user();
        $row  = (new UserProfileModel())->find($user->id);

        return view('profile/index', [
            'title' => 'Profil Saya',
            'row'   => $row,
        ]);
    }

    public function update()
    {
        $userId = auth()->user()->id;

        $data = $this->request->getPost(['phone','address']);

        (new UserProfileModel())->update($userId, $data);

        return redirect()->back()->with('success', 'Profil diperbarui.');
    }

    public function uploadAvatar()
    {
        $userId = auth()->user()->id;
        $file = $this->request->getFile('avatar');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        // validasi dasar
        $valid = $this->validate([
            'avatar' => 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]',
        ]);

        if (!$valid) {
            return redirect()->back()->with('error', 'Avatar harus gambar (jpg/png/webp) max 2MB.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/avatars', $newName);

        (new UserProfileModel())->update($userId, ['avatar' => $newName]);

        return redirect()->back()->with('success', 'Foto profil berhasil diubah.');
    }
}
