<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'speaker',
        'target_audience',
        'quota',
        'poster_image',
        'status',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'event_date'   => 'date',
        'is_published' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($agenda) {
            if (empty($agenda->slug)) {
                $agenda->slug = Str::slug($agenda->title) . '-' . Str::random(5);
            }
        });

        static::updating(function ($agenda) {
            if ($agenda->isDirty('title') && empty($agenda->slug)) {
                $agenda->slug = Str::slug($agenda->title) . '-' . Str::random(5);
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedTimeAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_time)->format('H:i');
        if ($this->end_time) {
            $end = \Carbon\Carbon::parse($this->end_time)->format('H:i');
            return "{$start} - {$end} WIB";
        }
        return "{$start} WIB";
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('speaker', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }
}
