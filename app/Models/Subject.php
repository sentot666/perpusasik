<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    protected $fillable = ['name', 'ddc'];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_subject');
    }
}
