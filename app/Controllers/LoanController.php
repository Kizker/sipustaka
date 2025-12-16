<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\LoanModel;
use CodeIgniter\I18n\Time;

class LoanController extends BaseController
{
    public function myLoans()
    {
        $userId = auth()->user()->id;

        $loans = (new LoanModel())
            ->select('loans.*, books.title, books.author')
            ->join('books', 'books.id = loans.book_id')
            ->where('loans.user_id', $userId)
            ->orderBy('loans.id', 'DESC')
            ->findAll();

        return view('loans/my', ['title' => 'Peminjaman Saya', 'loans' => $loans]);
    }

    public function borrow(int $bookId)
    {
        $userId = auth()->user()->id;

        $bookModel = new BookModel();
        $loanModel = new LoanModel();

        $book = $bookModel->find($bookId);
        if (!$book) return redirect()->back()->with('error', 'Buku tidak ditemukan.');

        if ((int)$book['stock'] <= 0) {
            return redirect()->back()->with('error', 'Stok habis.');
        }

        // Cegah pinjam buku yang sama jika masih aktif
        $exists = $loanModel->where([
            'user_id' => $userId,
            'book_id' => $bookId,
            'status'  => 'borrowed',
        ])->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Buku ini masih kamu pinjam.');
        }

        $borrowDate = $this->request->getPost('borrow_date'); // Y-m-d
        $dueDate    = $this->request->getPost('due_date');    // Y-m-d

        $borrowedAt = \CodeIgniter\I18n\Time::parse($borrowDate . ' 08:00:00');
        $dueAt      = \CodeIgniter\I18n\Time::parse($dueDate . ' 17:00:00');

        $loanModel->insert([
            'user_id' => $userId,
            'book_id' => $bookId,
            'borrowed_at' => $borrowedAt->toDateTimeString(),
            'due_at' => $dueAt->toDateTimeString(),
            'status' => 'borrowed',
        ]);

        $bookModel->update($bookId, ['stock' => (int)$book['stock'] - 1]);

        return redirect()->to('/my-loans')->with('success', 'Berhasil meminjam buku.');
    }

    public function return(int $loanId)
    {
        $userId = auth()->user()->id;

        $loanModel = new LoanModel();
        $bookModel = new BookModel();

        $loan = $loanModel->find($loanId);
        if (!$loan) return redirect()->back()->with('error', 'Data pinjam tidak ditemukan.');

        if ((int)$loan['user_id'] !== (int)$userId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        if ($loan['status'] !== 'borrowed') {
            return redirect()->back()->with('error', 'Transaksi sudah selesai.');
        }

        $loanModel->update($loanId, [
            'status' => 'returned',
            'returned_at' => Time::now()->toDateTimeString(),
        ]);

        $book = $bookModel->find((int)$loan['book_id']);
        if ($book) {
            $bookModel->update((int)$loan['book_id'], ['stock' => (int)$book['stock'] + 1]);
        }

        return redirect()->to('/my-loans')->with('success', 'Buku berhasil dikembalikan.');
    }
}
