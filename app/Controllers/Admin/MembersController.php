<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserProfileModel;

class MembersController extends BaseController
{
    public function index()
    {
        $model = new UserProfileModel();
        $q = trim((string)$this->request->getGet('q'));

        if ($q !== '') {
            $model->groupStart()
                ->like('username', $q)
                ->orLike('member_no', $q)
                ->orLike('phone', $q)
                ->groupEnd();
        }

        $rows = $model->orderBy('id','DESC')->paginate(15);

        return view('admin/members/index', [
            'title' => 'Manajemen Anggota',
            'rows'  => $rows,
            'pager' => $model->pager,
            'q'     => $q,
        ]);
    }

    public function edit(int $id)
    {
        $row = (new UserProfileModel())->find($id);
        if (!$row) return redirect()->to('/admin/members')->with('error','Anggota tidak ditemukan.');

        return view('admin/members/edit', [
            'title' => 'Edit Anggota',
            'row'   => $row,
        ]);
    }

    public function update(int $id)
    {
        $data = $this->request->getPost(['phone','address']);
        (new UserProfileModel())->update($id, $data);

        return redirect()->to('/admin/members')->with('success','Data anggota diperbarui.');
    }
}
