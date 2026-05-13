<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubEvent extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = ['event_id','report_title', 'event_date', 'attendees_count', 'comment', 'youtube_url', 'photos','photo_priority'];

    // Para manejar las fotos como array automáticamente
    protected $casts = [
        'photos' => 'array',
        'photo_priority' => 'array',
        'event_date' => 'date'
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
