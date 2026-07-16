<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    protected $fillable = ['name', 'type', 'biography'];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_author')
                    ->withPivot('role', 'order');
    }
}
