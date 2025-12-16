<?php

namespace App\Controllers;

use App\Models\BookModel;

class CatalogController extends BaseController
{
    public function index()
{
    $model = new \App\Models\BookModel();

    $books = $model->orderBy('id', 'DESC')->paginate(10);

    return view('catalog/index', [
        'title'   => 'SiPustaka',
        'tagline' => 'Layanan Busa Pustaka',
        'books'   => $books,
        'pager'   => $model->pager, // ✅ ambil pager dari instance yang sama
    ]);
}

    public function show(int $id)
    {
        $book = (new BookModel())->find($id);
        if (!$book) return redirect()->to('/');

        return view('catalog/show', [
            'title' => $book['title'],
            'book'  => $book,
        ]);
    }
}
