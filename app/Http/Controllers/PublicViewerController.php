<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicViewerController extends Controller
{
    public function index()
    {
        // Traemos todos los eventos (metas) con sus respectivos sub-eventos (reportes)
        // Ordenamos los reportes de los más recientes a los más antiguos
        $actividades = Event::with(['category', 'subEvents' => function($query) {
            $query->orderBy('event_date', 'desc');
        }])->get();

        return view('welcome', compact('actividades'));
    }
}

