<?php

namespace App\Models;

use CodeIgniter\Model;

class LoanModel extends Model
{
    protected $table = 'loans';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id','book_id','borrowed_at','due_at','returned_at','status'
    ];

    protected $useTimestamps = true;
}
