<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookModel;

class BooksController extends BaseController
{
    private function uploadCover(?string $oldFile = null): ?string
    {
        $file = $this->request->getFile('cover');

        // Tidak upload apa pun
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        // Validasi file gambar
        $valid = $this->validate([
            'cover' => 'uploaded[cover]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png,image/webp]|max_size[cover,2048]'
        ]);

        if (!$valid) {
            // lempar error validasi ke form
            return 'INVALID';
        }

        // Pastikan folder ada
        $dir = FCPATH . 'uploads/covers';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($dir, $newName);

        // Hapus file lama kalau ada
        if ($oldFile) {
            $oldPath = $dir . DIRECTORY_SEPARATOR . $oldFile;
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return $newName;
    }

    public function index()
    {
        $model = new BookModel();
        $q = trim((string) $this->request->getGet('q'));

        if ($q !== '') {
            $model->groupStart()
                ->like('title', $q)
                ->orLike('author', $q)
                ->orLike('isbn', $q)
                ->groupEnd();
        }

        $books = $model->orderBy('id', 'DESC')->paginate(10);

        return view('admin/books/index', [
            'title' => 'Kelola Buku',
            'books' => $books,
            'pager' => $model->pager,
            'q'     => $q,
        ]);
    }

    public function new()
    {
        return view('admin/books/new', ['title' => 'Tambah Buku']);
    }

    public function create()
    {
        $rules = [
            'title'  => 'required|min_length[3]',
            'author' => 'required|min_length[3]',
            'stock'  => 'required|is_natural',
            'year'   => 'permit_empty|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost([
            'isbn','title','author','publisher','year','category','description','stock'
        ]);

        // Upload cover (jika ada)
        $uploaded = $this->uploadCover();
        if ($uploaded === 'INVALID') {
            return redirect()->back()->withInput()->with('errors', ['cover' => 'Cover harus gambar (jpg/png/webp) max 2MB.']);
        }
        if ($uploaded) {
            $data['cover'] = $uploaded;
        }

        (new BookModel())->insert($data);

        return redirect()->to('/admin/books')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $book = (new BookModel())->find($id);
        if (!$book) return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan.');

        return view('admin/books/edit', [
            'title' => 'Edit Buku',
            'book'  => $book,
        ]);
    }

    public function update(int $id)
    {
        $model = new BookModel();
        $book  = $model->find($id);
        if (!$book) return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan.');

        $rules = [
            'title'  => 'required|min_length[3]',
            'author' => 'required|min_length[3]',
            'stock'  => 'required|is_natural',
            'year'   => 'permit_empty|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost([
            'isbn','title','author','publisher','year','category','description','stock'
        ]);

        // Upload cover baru (jika ada), hapus yang lama
        $uploaded = $this->uploadCover($book['cover'] ?? null);
        if ($uploaded === 'INVALID') {
            return redirect()->back()->withInput()->with('errors', ['cover' => 'Cover harus gambar (jpg/png/webp) max 2MB.']);
        }
        if ($uploaded) {
            $data['cover'] = $uploaded;
        }

        $model->update($id, $data);

        return redirect()->to('/admin/books')->with('success', 'Buku berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new BookModel();
        $book  = $model->find($id);

        if ($book) {
            // hapus file cover
            $cover = $book['cover'] ?? null;
            if ($cover) {
                $path = FCPATH . 'uploads/covers/' . $cover;
                if (is_file($path)) {
                    @unlink($path);
                }
            }
            $model->delete($id);
        }

        return redirect()->to('/admin/books')->with('success', 'Buku berhasil dihapus.');
    }
}
