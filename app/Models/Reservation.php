<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model

{
    protected $fillable = [
        'member_id',
        'book_id',
        'book_item_id',
        'reserve_date',
        'expired_date',
        'status',
    ];

    protected $casts = [
        'reserve_date' => 'date',
        'expired_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function bookItem()
    {
        return $this->belongsTo(BookItem::class);
    }
}
