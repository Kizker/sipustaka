<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookModel;

class BooksController extends BaseController
{
    public function index()
    {
        $model = new BookModel();
        $q = $this->request->getGet('q');

        $builder = $model->orderBy('id', 'DESC');
        if ($q) {
            $builder = $model->groupStart()
                ->like('title', $q)
                ->orLike('author', $q)
                ->groupEnd()
                ->orderBy('id', 'DESC');
        }

        $books = $builder->paginate(10);

        return view('admin/books/index', [
            'title' => 'Kelola Buku',
            'books' => $books,
            'pager' => $model->pager,
            'q' => $q,
        ]);
    }

    public function new()
    {
        return view('admin/books/new', ['title' => 'Tambah Buku']);
    }

    public function create()
    {
        $data = $this->request->getPost([
            'isbn','title','author','publisher','year','category','description','stock'
        ]);

        $rules = [
            'title' => 'required|min_length[3]',
            'author' => 'required|min_length[3]',
            'stock' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new BookModel())->insert($data);
        return redirect()->to('/admin/books')->with('success', 'Buku ditambahkan.');
    }

    public function edit(int $id)
    {
        $book = (new BookModel())->find($id);
        if (!$book) return redirect()->to('/admin/books');

        return view('admin/books/edit', ['title' => 'Edit Buku', 'book' => $book]);
    }

    public function update(int $id)
    {
        $data = $this->request->getPost([
            'isbn','title','author','publisher','year','category','description','stock'
        ]);

        (new BookModel())->update($id, $data);
        return redirect()->to('/admin/books')->with('success', 'Buku diperbarui.');
    }

    public function delete(int $id)
    {
        (new BookModel())->delete($id);
        return redirect()->to('/admin/books')->with('success', 'Buku dihapus.');
    }
}
