<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\EventProgressExport;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
{
    // Mostrar la lista de Actividades Operativas
    public function index()
    {
        $events = Event::with('category')->get();
        return view('events.index', compact('events'));
    }

    // Mostrar el formulario para crear
    public function create()
    {
        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    // Guardar en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'event_code' => 'required|string|unique:events,event_code',
            'poi_code' => 'nullable|string',
            'description' => 'required|string',
            'unit_measure' => 'required|string',
            'goal_people' => 'required|integer|min:1',
        ]);

        Event::create($request->all());

        return redirect()->route('events.index')
            ->with('success', 'Actividad Operativa creada y vinculada con éxito.');
    }

    // Mostrar el detalle de una actividad
    public function show(Event $event)
    {
        $event->load('category', 'subEvents');
        return view('events.show', compact('event'));
    }

    // Mostrar el formulario para editar
    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('events.edit', compact('event', 'categories'));
    }

    // Actualizar la actividad
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'event_code' => 'required|string|unique:events,event_code,' . $event->id,
            'poi_code' => 'nullable|string',
            'description' => 'required|string',
            'unit_measure' => 'required|string',
            'goal_people' => 'required|integer|min:1',
        ]);

        $event->update($request->all());

        return redirect()->route('events.index')
            ->with('success', 'Actividad actualizada correctamente.');
    }

    // Soft Delete (mover a papelera)
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')
            ->with('success', 'Actividad movida a la papelera.');
    }

    // Generar informe Excel
    public function report(Event $event)
    {
        return Excel::download(new EventProgressExport($event), 'reporte-' . $event->event_code . '.xlsx');
    }
}