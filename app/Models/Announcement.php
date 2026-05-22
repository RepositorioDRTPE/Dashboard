<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'file_path', 'file_type', 'published_at', 'expired_at'];

    protected $casts = [
        'published_at' => 'date',
        'expired_at'   => 'date',
    ];
}

