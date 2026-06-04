<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    // 🎯 REPARADO: Se añaden document_path y requirements_path para permitir su guardado
    protected $fillable = [
        'title', 
        'description', 
        'type', 
        'scheduled_at', 
        'document_path', 
        'requirements_path', 
        'photos'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'photos'       => 'array', // Parsea automáticamente el JSON a array nativo de PHP
    ];
}

