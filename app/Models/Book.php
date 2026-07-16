<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'subtitle', 'isbn', 'isbn13', 'call_number', 'ddc',
        'edition', 'language', 'publication_year', 'place_of_publication',
        'pages', 'dimensions', 'series_title', 'series_number',
        'abstract', 'notes', 'cover_image', 'collection_type',
        'is_active', 'publisher_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'pages'     => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_author')
                    ->withPivot('role', 'order')
                    ->orderByPivot('order');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'book_subject');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookItem::class);
    }

    public function availableItems(): HasMany
    {
        return $this->hasMany(BookItem::class)->where('status', 'Tersedia');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getAvailableCopiesAttribute(): int
    {
        return $this->items()->where('status', 'Tersedia')->count();
    }

    public function getTotalCopiesAttribute(): int
    {
        return $this->items()->count();
    }

    public function getMainAuthorAttribute(): ?string
    {
        return $this->authors()->wherePivot('order', 1)->first()?->name;
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('isbn', 'like', "%{$term}%")
              ->orWhere('isbn13', 'like', "%{$term}%")
              ->orWhere('call_number', 'like', "%{$term}%")
              ->orWhereHas('authors', fn($a) => $a->where('name', 'like', "%{$term}%"))
              ->orWhereHas('subjects', fn($s) => $s->where('name', 'like', "%{$term}%"))
              ->orWhereHas('publisher', fn($p) => $p->where('name', 'like', "%{$term}%"));
        });
    }

    public function scopeAvailable($query)
    {
        return $query->whereHas('items', fn($q) => $q->where('status', 'Tersedia'));
    }
}
