<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SubEvent;
use Illuminate\Http\Request;

class SubEventController extends Controller
{
    public function index()
    {
        $subEvents = SubEvent::with('event')->orderBy('event_date', 'desc')->get();
        return view('subevents.index', compact('subEvents'));
    }

    public function create()
    {
        $events = Event::all();
        return view('subevents.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'report_title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'attendees_count' => 'required|integer|min:1',
            'comment' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('event_photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Al crear, asignamos prioridades secuenciales por defecto
        $priorities = range(1, count($photoPaths));

        SubEvent::create([
            'event_id' => $request->event_id,
            'report_title' => $request->report_title,
            'event_date' => $request->event_date,
            'attendees_count' => $request->attendees_count,
            'comment' => $request->comment,
            'youtube_url' => $request->youtube_url,
            'photos' => $photoPaths,
            'photo_priority' => $priorities,
        ]);

        return redirect()->route('subevents.index')
            ->with('success', 'Reporte de avance guardado exitosamente.');
    }

    public function show(SubEvent $subevent)
    {
        return view('subevents.show', compact('subevent'));
    }

    public function edit(SubEvent $subevent)
    {
        $events = Event::all();
        return view('subevents.edit', compact('subevent', 'events'));
    }

    public function update(Request $request, SubEvent $subevent)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'report_title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'attendees_count' => 'required|integer|min:1',
            'comment' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'photo_order' => 'nullable|array',
            'photo_priority' => 'nullable|array',
        ]);

        // Obtener fotos existentes y sus prioridades actuales
        $currentPhotos = $subevent->photos ?? [];
        $currentPriorities = $subevent->photo_priority ?? range(1, count($currentPhotos));

        // Procesar nuevas fotos subidas
        $newPhotoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('event_photos', 'public');
                $newPhotoPaths[] = $path;
            }
        }

        // Lógica de ordenamiento con prioridades
        if ($request->has('photo_order') && $request->has('photo_priority')) {
            $orderedPhotos = $request->input('photo_order', []);
            $orderedPriorities = $request->input('photo_priority', []);

            // Crear un mapa de foto => prioridad desde los datos enviados
            $priorityMap = [];
            foreach ($orderedPhotos as $index => $photoPath) {
                if (isset($orderedPriorities[$index])) {
                    $priorityMap[$photoPath] = (int) $orderedPriorities[$index];
                }
            }

            // Reordenar las fotos existentes según el orden enviado
            $sortedExistingPhotos = [];
            foreach ($orderedPhotos as $photo) {
                if (in_array($photo, $currentPhotos)) {
                    $sortedExistingPhotos[] = $photo;
                }
            }

            // Agregar cualquier foto existente que no estuviera en el orden enviado (por seguridad)
            foreach ($currentPhotos as $photo) {
                if (!in_array($photo, $sortedExistingPhotos)) {
                    $sortedExistingPhotos[] = $photo;
                }
            }

            // Las nuevas fotos se añaden al final
            $allPhotos = array_merge($sortedExistingPhotos, $newPhotoPaths);

            // Asignar prioridades finales
            $finalPriorities = [];
            $nextPriority = count($allPhotos) + 1;
            foreach ($allPhotos as $photo) {
                if (isset($priorityMap[$photo])) {
                    $finalPriorities[] = $priorityMap[$photo];
                } else {
                    $finalPriorities[] = $nextPriority++;
                }
            }
        } else {
            // Si no se envió orden, simplemente añadir nuevas fotos al final con prioridades incrementales
            $allPhotos = array_merge($currentPhotos, $newPhotoPaths);
            $finalPriorities = range(1, count($allPhotos));
        }

        // Actualizar el modelo
        $subevent->update([
            'event_id' => $request->event_id,
            'report_title' => $request->report_title,
            'event_date' => $request->event_date,
            'attendees_count' => $request->attendees_count,
            'comment' => $request->comment,
            'youtube_url' => $request->youtube_url,
            'photos' => $allPhotos,
            'photo_priority' => $finalPriorities,
        ]);

        return redirect()->route('subevents.index')
            ->with('success', 'Reporte actualizado correctamente.');
    }

    // Soft Delete (mover a papelera)
    public function destroy(SubEvent $subevent)
    {
        $subevent->delete();
        return redirect()->route('subevents.index')
            ->with('success', 'Reporte movido a la papelera.');
    }

    // Listar elementos eliminados (papelera)
    public function trashed()
    {
        $subEvents = SubEvent::onlyTrashed()->with('event')->orderBy('deleted_at', 'desc')->get();
        return view('subevents.trashed', compact('subEvents'));
    }

    // Restaurar desde papelera
    public function restore($id)
    {
        $subEvent = SubEvent::onlyTrashed()->findOrFail($id);
        $subEvent->restore();
        return redirect()->route('subevents.trashed')
            ->with('success', 'Reporte restaurado exitosamente.');
    }

    // Eliminación permanente
    public function forceDelete($id)
    {
        $subEvent = SubEvent::onlyTrashed()->findOrFail($id);
        
        // Eliminar fotos del almacenamiento antes de borrar definitivamente
        if ($subEvent->photos) {
            foreach ($subEvent->photos as $photo) {
                \Storage::disk('public')->delete($photo);
            }
        }
        
        $subEvent->forceDelete();
        return redirect()->route('subevents.trashed')
            ->with('success', 'Reporte eliminado permanentemente.');
    }
}