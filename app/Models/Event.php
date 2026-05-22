<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes; 

    protected $fillable = ['category_id', 'event_code', 'title', 'goal_people', 'unit_measure','description','funding_source']; 

    public function category() { 
        return $this->belongsTo(Category::class); 
        } 
    public function subEvents() { 
        return $this->hasMany(SubEvent::class); 
        }
    public function getActualProgressAttribute(){
        return $this->subEvents()->sum('attendees_count');
    }
    
    // Cálculo de progreso para el gráfico de torta 
    public function getProgressPercentageAttribute() {  
        if ($this->goal_people <= 0) return 0; 
        return round($actual_progress / $this->goal_people) * 100; 
        }
}
