<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubEventController;
use App\Http\Controllers\PublicViewerController;
use App\Models\Event;
use App\Models\SubEvent;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PhotoReportController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\WorkshopController;




Route::get('/', [PublicViewerController::class, 'index'])->name('public.viewer');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard interno (reemplazado con la versión completa para gráficos)
    Route::get('/dashboard', function () {
        $totalMetas = Event::sum('goal_people');
        $totalAvance = SubEvent::sum('attendees_count');
        $porcentajeGlobal = $totalMetas > 0 ? round(($totalAvance / $totalMetas) * 100, 1) : 0;

        // Eventos con avance sumado (sin cargar toda la relación)
        $eventos = Event::with('category')
            ->withSum('subEvents as total_attendees', 'attendees_count')
            ->get();

        // Datos para gráfico de barras
        $chartBar = $eventos->map(function ($e) {
            return [
                'code'   => $e->event_code,
                'avance' => (int)($e->total_attendees ?? 0),
                'meta'   => (int)$e->goal_people,
            ];
        })->values();

        // Avance por categoría
        $catData = $eventos->groupBy(function ($e) {
            return $e->category->name ?? 'Sin categoría';
        })->map(function ($items) {
            return $items->sum('total_attendees');
        });

        // Evolución mensual (últimos 24 meses con datos)
        $monthly = SubEvent::selectRaw("DATE_FORMAT(event_date, '%Y-%m') as mes, SUM(attendees_count) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->limit(24)
            ->get();

        // Actividades completadas (avance >= meta)
        $completadas = $eventos->filter(fn($e) => ($e->total_attendees ?? 0) >= $e->goal_people)->count();
        $totalActividades = $eventos->count();

        return view('dashboard', compact(
            'totalMetas',
            'totalAvance',
            'porcentajeGlobal',
            'eventos',
            'chartBar',
            'catData',
            'monthly',
            'completadas',
            'totalActividades'
        ));
    })->name('dashboard');

    // ========================
    // SUBEVENTOS (Reportes de Avance)
    // ========================
    Route::get('/subevents/trashed', [SubEventController::class, 'trashed'])->name('subevents.trashed');
    Route::post('/subevents/{id}/restore', [SubEventController::class, 'restore'])->name('subevents.restore');
    Route::delete('/subevents/{id}/force-delete', [SubEventController::class, 'forceDelete'])->name('subevents.force-delete');
    Route::resource('subevents', SubEventController::class);

    // ========================
    // EVENTOS (Actividades Operativas)
    // ========================
    Route::get('/events/trashed', [EventController::class, 'trashed'])->name('events.trashed');
    Route::post('/events/{id}/restore', [EventController::class, 'restore'])->name('events.restore');
    Route::get('/events/{event}/report', [EventController::class, 'report'])->name('events.report');
    Route::resource('events', EventController::class);

    // ========================
    // CATEGORÍAS (Actividades Generales PP)
    // ========================
    Route::get('/categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::resource('categories', CategoryController::class);

    // ========================
    // PAPELERA GENERAL (opcional)
    // ========================
    Route::get('/papelera', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/papelera/restaurar/{tipo}/{id}', [TrashController::class, 'restore'])->name('trash.restore');

    // ========================
    // REPORTES
    // ========================
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/general', [ReportController::class, 'generateGeneral'])->name('reports.generate.general');
    Route::get('/reports/specific', [ReportController::class, 'generateSpecific'])->name('reports.generate.specific');
        // ESTO DEBE ESTAR AQUÍ ADENTRO DEL GRUPO 'auth'
    Route::get('/photo-reports', [App\Http\Controllers\PhotoReportController::class, 'index'])->name('photo-reports.index');
    Route::get('/photo-reports/create', [App\Http\Controllers\PhotoReportController::class, 'create'])->name('photo-reports.create');
    Route::post('/photo-reports', [App\Http\Controllers\PhotoReportController::class, 'store'])->name('photo-reports.store');


    Route::resource('bulletins', BulletinController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('workshops', WorkshopController::class);    


});

require __DIR__.'/auth.php';