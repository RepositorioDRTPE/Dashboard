<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'photos'
    ];

    // CONVERSIÓN AUTOMÁTICA DE ARRAY A JSON
    protected $casts = [
        'photos' => 'array',
    ];
}
