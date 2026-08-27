<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassLoan extends Model
{
    protected $fillable = [
        'borrower_name',
        'origin',
        'book_type',
        'quantity',
        'status',
        'loan_date',
        'return_date',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
