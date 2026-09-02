<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Setting;

class Circulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code', 'member_id', 'book_item_id', 'user_id',
        'loan_date', 'due_date', 'return_date', 'renewal_count',
        'status', 'fine_amount', 'fine_paid', 'fine_paid_at', 'notes',
    ];

    protected $casts = [
        'loan_date'    => 'date',
        'due_date'     => 'date',
        'return_date'  => 'date',
        'fine_amount'  => 'decimal:2',
        'fine_paid'    => 'boolean',
        'fine_paid_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function bookItem(): BelongsTo
    {
        return $this->belongsTo(BookItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'Dipinjam' && $this->due_date->isPast();
    }

    public function getDaysOverdueAttribute(): int
    {
        if (! $this->is_overdue) return 0;
        return $this->due_date->diffInDays(now());
    }

    public function getCalculatedFineAttribute(): float
    {
        $finePerDay = (float) Setting::get('fine_per_day', 1000);
        return $this->days_overdue * $finePerDay;
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'Dipinjam');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'Dipinjam')->where('due_date', '<', now()->toDateString());
    }

    // ── Static ────────────────────────────────────────────────────────────────

    public static function generateCode(): string
    {
        $prefix = 'TRX' . now()->format('Ymd');
        $last   = static::where('transaction_code', 'like', $prefix . '%')->max('transaction_code');
        $seq    = $last ? (intval(substr($last, -4)) + 1) : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
