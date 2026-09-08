<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'day',
        'date',
        'time',
        'class_name',
        'teacher_name',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
