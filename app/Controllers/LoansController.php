<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LoanModel;

class LoansController extends BaseController
{
    public function index()
    {
        $loans = (new LoanModel())
            ->select('loans.*, books.title, users.username, users.email')
            ->join('books', 'books.id = loans.book_id')
            ->join('users', 'users.id = loans.user_id')
            ->orderBy('loans.id', 'DESC')
            ->findAll();

        return view('admin/loans/index', ['title' => 'Data Peminjaman', 'loans' => $loans]);
    }
}
