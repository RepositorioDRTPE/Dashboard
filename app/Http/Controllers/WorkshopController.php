<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkshopController extends Controller
{
    public function index()
    {
        $workshops = Workshop::latest()->paginate(10);
        return view('workshops.index', compact('workshops'));
    }

    public function create()
    {
        return view('workshops.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'type'         => 'required|in:capacitacion,coordinacion',
            'scheduled_at' => 'required|date', // Valida tanto fecha sola como fecha/hora
            'document'     => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
            'requirements' => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
        ]);

        $data = $request->only(['title', 'description', 'type', 'scheduled_at']);

        // 1. Archivo obligatorio/opcional informativo (¿Qué hacen?)
        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('workshops/documents', 'public');
        }

        // 2. Bases o Requisitos (Solo si es capacitación)
        if ($request->type === 'capacitacion' && $request->hasFile('requirements')) {
            $data['requirements_path'] = $request->file('requirements')->store('workshops/requirements', 'public');
        }

        Workshop::create($data);

        return redirect()->route('workshops.index')->with('success', 'Actividad registrada con éxito.');
    }

    public function edit(Workshop $workshop)
    {
        return view('workshops.edit', compact('workshop'));
    }

    public function update(Request $request, Workshop $workshop)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'scheduled_at' => 'required|date',
            'document'     => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
            'requirements' => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
            'images.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->only(['title', 'description', 'scheduled_at']);

        // Actualizar Documento Informativo
        if ($request->hasFile('document')) {
            if ($workshop->document_path) { Storage::disk('public')->delete($workshop->document_path); }
            $data['document_path'] = $request->file('document')->store('workshops/documents', 'public');
        }

        // Actualizar Bases o Requisitos
        if ($workshop->type === 'capacitacion' && $request->hasFile('requirements')) {
            if ($workshop->requirements_path) { Storage::disk('public')->delete($workshop->requirements_path); }
            $data['requirements_path'] = $request->file('requirements')->store('workshops/requirements', 'public');
        }

        // Carga de Fotos de la galería si el evento ya concluyó
        if ($request->hasFile('images')) {
            $uploadedPhotos = $workshop->photos ?? [];
            foreach ($request->file('images') as $image) {
                $uploadedPhotos[] = $image->store('workshops/evidence', 'public');
            }
            $data['photos'] = $uploadedPhotos;
        }

        $workshop->update($data);

        return redirect()->route('workshops.index')->with('success', 'Registro actualizado de forma limpia.');
    }

    public function destroy(Workshop $workshop)
    {
        if ($workshop->document_path) { Storage::disk('public')->delete($workshop->document_path); }
        if ($workshop->requirements_path) { Storage::disk('public')->delete($workshop->requirements_path); }
        if ($workshop->photos) {
            foreach ($workshop->photos as $photo) { Storage::disk('public')->delete($photo); }
        }
        $workshop->delete();
        return redirect()->route('workshops.index')->with('success', 'Evento eliminado de la base de datos.');
    }
}

