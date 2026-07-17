<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_code', 'name', 'email', 'phone', 'identity_number',
        'identity_type', 'gender', 'address', 'city', 'province',
        'postal_code', 'birth_date', 'birth_place', 'education',
        'occupation', 'institution', 'member_type', 'register_date',
        'expired_date', 'is_active', 'photo', 'notes', 'barcode',
    ];

    protected $casts = [
        'birth_date'    => 'date',
        'register_date' => 'date',
        'expired_date'  => 'date',
        'is_active'     => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function circulations(): HasMany
    {
        return $this->hasMany(Circulation::class);
    }

    public function activeCirculations(): HasMany
    {
        return $this->hasMany(Circulation::class)->where('status', 'Dipinjam');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getIsExpiredAttribute(): bool
    {
        return $this->expired_date && $this->expired_date->isPast();
    }

    public function getStatusLabelAttribute(): string
    {
        if (! $this->is_active) return 'Nonaktif';
        if ($this->is_expired) return 'Kedaluwarsa';
        return 'Aktif';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status_label) {
            'Aktif'       => 'success',
            'Kedaluwarsa' => 'warning',
            default       => 'secondary',
        };
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('member_code', 'like', "%{$term}%")
              ->orWhere('barcode', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('identity_number', 'like', "%{$term}%");
        });
    }

    // ── Static Helpers ────────────────────────────────────────────────────────

    public static function generateCode(): string
    {
        $year  = now()->format('Y');
        $last  = static::whereYear('created_at', $year)->max('member_code');
        $seq   = $last ? (intval(substr($last, -4)) + 1) : 1;
        return 'M' . $year . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
