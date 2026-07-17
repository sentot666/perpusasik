<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class GuestBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_date',
        'name',
        'institution',
        'purpose',
        'visit_time',
        'participants_count',
        'notes',
    ];

    protected $casts = [
        'visit_date'         => 'date',
        'participants_count' => 'integer',
    ];

    /**
     * Accessor for Hari & Tanggal (Indonesian Format)
     */
    public function getFormattedDateAttribute(): string
    {
        if (!$this->visit_date) return '-';

        $days = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];

        $months = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $date = Carbon::parse($this->visit_date);
        $dayName = $days[$date->format('l')] ?? $date->format('l');
        $monthName = $months[$date->month] ?? $date->format('F');

        return "{$dayName}, {$date->day} {$monthName} {$date->year}";
    }

    /**
     * Accessor for formatted visit time
     */
    public function getFormattedTimeAttribute(): string
    {
        if (!$this->visit_time) return '-';
        return Carbon::parse($this->visit_time)->format('H:i') . ' WIB';
    }
}
