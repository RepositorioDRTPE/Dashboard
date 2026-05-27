<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'type', 'scheduled_at', 'photos'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'photos'       => 'array', // Parsea el JSON a array nativo de PHP
    ];
}
