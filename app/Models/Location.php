<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function bookItems(): HasMany
    {
        return $this->hasMany(BookItem::class);
    }
}
