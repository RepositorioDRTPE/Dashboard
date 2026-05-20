<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PhotoReport; // <-- IMPORTANTE: Agregamos el nuevo modelo
use Illuminate\Http\Request;

class PublicViewerController extends Controller
{
    public function index()
    {
        // 1. Traemos todos los eventos (metas) con sus respectivos sub-eventos (reportes)
        // Ordenamos los reportes de los más recientes a los más antiguos
        $actividades = Event::with(['category', 'subEvents' => function($query) {
            $query->orderBy('event_date', 'desc');
        }])->get();

        // 2. NUEVO: Traemos los últimos reportes fotográficos (Impacto y Difusión)
        // Usamos latest() para que salgan los más nuevos primero, y take(8) para no sobrecargar el carrusel
        $photoReports = PhotoReport::latest()->take(8)->get();

        // 3. Pasamos ambas variables a la vista
        return view('welcome', compact('actividades', 'photoReports'));
    }
}


