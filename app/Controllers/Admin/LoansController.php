<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\BookModel;
use CodeIgniter\I18n\Time;

class LoansController extends BaseController
{
    public function index()
{
    $model  = new \App\Models\LoanModel();
    $status = $this->request->getGet('status'); // borrowed|returned|all

    $model->select('loans.*, books.title AS book_title, books.author AS book_author, users.username,
                    ai.secret AS email')
        ->join('books', 'books.id = loans.book_id', 'left')
        ->join('users', 'users.id = loans.user_id', 'left')
        // ambil email dari auth_identities (Shield)
        ->join("auth_identities ai", "ai.user_id = users.id AND ai.type = 'email_password'", 'left')
        ->orderBy('loans.id', 'DESC');

    if ($status && $status !== 'all') {
        $model->where('loans.status', $status);
    }

    $loans = $model->paginate(15);

    return view('admin/loans/index', [
        'title'  => 'Data Peminjaman',
        'loans'  => $loans,
        'pager'  => $model->pager,
        'status' => $status ?? 'all',
    ]);
}


    public function markReturned(int $loanId)
    {
        $loanModel = new LoanModel();
        $bookModel = new BookModel();

        $loan = $loanModel->find($loanId);
        if (!$loan) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        if ($loan['status'] !== 'borrowed') {
            return redirect()->back()->with('error', 'Transaksi ini sudah selesai.');
        }

        // Update transaksi
        $loanModel->update($loanId, [
            'status'      => 'returned',
            'returned_at' => Time::now()->toDateTimeString(),
        ]);

        // Kembalikan stok buku
        $book = $bookModel->find((int)$loan['book_id']);
        if ($book) {
            $bookModel->update((int)$loan['book_id'], [
                'stock' => (int)$book['stock'] + 1
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil ditandai kembali.');
    }
}
