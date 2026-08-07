<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_id', 'barcode', 'accession_number', 'location_id',
        'condition', 'status', 'acquisition_date', 'acquisition_price',
        'acquisition_source', 'notes',
    ];

    protected $casts = [
        'acquisition_date'  => 'date',
        'acquisition_price' => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function circulations(): HasMany
    {
        return $this->hasMany(Circulation::class);
    }

    public function activeCirculation()
    {
        return $this->hasOne(Circulation::class)->where('status', 'Dipinjam')->latest();
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', 'Tersedia');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getCopyNumberAttribute(): int
    {
        if (!$this->book_id) return 1;
        return static::where('book_id', $this->book_id)
            ->where('id', '<=', $this->id)
            ->count() ?: 1;
    }

    public function getCopyCodeAttribute(): string
    {
        return 'c' . $this->copy_number;
    }

    // ── Static Helpers ────────────────────────────────────────────────────────

    public static function generateAccessionNumber(): string
    {
        $year  = now()->format('Y');
        $last  = static::whereYear('created_at', $year)->max('accession_number');
        $seq   = $last ? (intval(substr($last, -5)) + 1) : 1;
        return $year . '.' . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }
}
