<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookModel;
use App\Models\LoanModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $bookModel = new BookModel();
        $loanModel = new LoanModel();
        $db = db_connect();

        $totalBooks   = $bookModel->countAllResults();
        $totalStock   = (int) ($db->table('books')->selectSum('stock')->get()->getRow()->stock ?? 0);

        $activeLoans  = $loanModel->where('status', 'borrowed')->countAllResults();
        $returnedLoans= $loanModel->where('status', 'returned')->countAllResults();

        $totalUsers   = (int) ($db->table('users')->countAllResults() ?? 0);

        return view('admin/dashboard', [
            'title'         => 'Dashboard Admin - SiPustaka',
            'totalBooks'    => $totalBooks,
            'totalStock'    => $totalStock,
            'activeLoans'   => $activeLoans,
            'returnedLoans' => $returnedLoans,
            'totalUsers'    => $totalUsers,
        ]);
    }
}
