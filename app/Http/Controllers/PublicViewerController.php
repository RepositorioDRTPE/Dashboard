<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PhotoReport;
use App\Models\Bulletin;      
use App\Models\Announcement;
use App\Models\Workshop;  
use Illuminate\Http\Request;

class PublicViewerController extends Controller
{
    public function index()
    {
        $now = now();
        $today = $now->toDateString();

        // 1. Cronología y actividades operativas base (Modelo Event)
        $actividades = Event::with(['category', 'subEvents' => function($query) {
            $query->orderBy('event_date', 'desc');
        }])->get();

        // 2. Reportes fotográficos generales (Sliders frontales)
        $photoReports = PhotoReport::latest()->take(8)->get();

        // 3. Separación de Difusiones y Eventos Institucionales para los Sliders
        $difusiones      = $photoReports->where('type', 'difusion')->values();
        $institucionales = $photoReports->where('type', 'evento')->values();

        // 4. Boletines Informativos para el footer claro
        $bulletins = Bulletin::latest()->take(4)->get();

        // 5. Comunicados Oficiales Vigentes (Pop-up automático y Alertas)
        $comunicadosActivos = Announcement::where('published_at', '<=', $today)
            ->where('expired_at', '>=', $today)
            ->latest()
            ->get();

        // 6. PROCESAMIENTO SEGURO: Ordenar fotos de sub-eventos por prioridad en el servidor
        $todosSubEventos = collect();
        foreach ($actividades as $aIdx => $act) {
            foreach ($act->subEvents as $se) {
                $se->category_name      = $act->category->name ?? 'General';
                $se->parent_description = $act->description;
                $se->activity_index     = $aIdx;  
               
                // Decodificamos las fotos reales
                $rawPh = is_string($se->photos) ? json_decode($se->photos, true) : ($se->photos ?? []);
                $photosArr = is_array($rawPh) ? $rawPh : [];
               
                // Decodificamos las prioridades asignadas
                $rawPrio = is_string($se->photo_priority) ? json_decode($se->photo_priority, true) : ($se->photo_priority ?? []);
                $prioArr = is_array($rawPrio) ? $rawPrio : [];
               
                // Si coinciden en tamaño, ordenamos por prioridad, si no, mantenemos el orden de subida
                if (!empty($prioArr) && count($prioArr) === count($photosArr)) {
                    $combined = array_combine($prioArr, $photosArr);
                    ksort($combined);
                    $se->photos_sorted = array_values($combined);
                } else {
                    $se->photos_sorted = $photosArr;
                }
               
                // Extraemos la primera foto como portada oficial
                $se->cover = count($se->photos_sorted) > 0 ? $se->photos_sorted[0] : null;
               
                $todosSubEventos->push($se);
            }
        }
       
        // Filtramos el Top 3 de registros recientes para el bloque "Últimos Registros"
        $ultimos3 = $todosSubEventos->filter(fn($s) => $s->cover !== null)
            ->sortByDesc('event_date')
            ->take(3)
            ->values();

        // 7. CONSULTAS DE NUEVO MÓDULO: Talleres regulados por el reloj del servidor
        // Capacitaciones Vigentes (Por Hacer)
        $capacitacionesPorHacer = Workshop::where('type', 'capacitacion')
            ->where('scheduled_at', '>=', $now)
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Capacitaciones Hechas (Pasadas)
        $capacitacionesHechas = Workshop::where('type', 'capacitacion')
            ->where('scheduled_at', '<', $now)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        // Reuniones de Coordinación Hechas
        $coordinacionesHechas = Workshop::where('type', 'coordinacion')
            ->orderBy('scheduled_at', 'desc')
            ->get();

        // 🎯 RETORNO SEGURO: Empaquetamos las 10 variables juntas hacia la vista welcome sin colisiones
        return view('welcome', compact(
            'actividades',
            'photoReports',
            'bulletins',
            'comunicadosActivos',
            'difusiones',
            'institucionales',
            'ultimos3',
            'capacitacionesPorHacer',
            'capacitacionesHechas',
            'coordinacionesHechas'
        ));
    }
}


