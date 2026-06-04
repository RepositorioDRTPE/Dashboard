<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkshopController extends Controller
{
    public function index()
    {
        // Paginación limpia ordenando por los registros más recientes
        $workshops = Workshop::latest()->paginate(10);
        return view('workshops.index', compact('workshops'));
    }

    public function create()
    {
        return view('workshops.create');
    }

    public function store(Request $request)
    {
        // 🎯 CORREGIDO: Eliminada la coma ilegal antes del operador de asignación '=>'
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'type'         => 'required|in:capacitacion,coordinacion',
            'scheduled_at' => 'required|date', 
            'document'     => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
            'requirements' => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
            'images.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->only(['title', 'description', 'type', 'scheduled_at']);

        // 1. Carga de Afiche o Documento Matriz Principal
        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('workshops/documents', 'public');
        }

        // 2. Bases Complementarias (Solo si aplica al módulo de capacitación)
        if ($request->type === 'capacitacion' && $request->hasFile('requirements')) {
            $data['requirements_path'] = $request->file('requirements')->store('workshops/requirements', 'public');
        }

        // 3. Soporte para evidencias fotográficas desde la creación inicial
        if ($request->hasFile('images')) {
            $photos = [];
            foreach ($request->file('images') as $image) {
                $photos[] = $image->store('workshops/evidence', 'public');
            }
            $data['photos'] = $photos; // El Modelo se encarga de convertirlo a JSON gracias al cast
        }

        Workshop::create($data);

        return redirect()->route('workshops.index')->with('success', 'Actividad registrada con éxito en el portal.');
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

        // Actualizar Documento Informativo y limpiar disco duro
        if ($request->hasFile('document')) {
            if ($workshop->document_path) { 
                Storage::disk('public')->delete($workshop->document_path); 
            }
            $data['document_path'] = $request->file('document')->store('workshops/documents', 'public');
        }

        // Actualizar Bases o Requisitos y limpiar disco duro
        if ($workshop->type === 'capacitacion' && $request->hasFile('requirements')) {
            if ($workshop->requirements_path) { 
                Storage::disk('public')->delete($workshop->requirements_path); 
            }
            $data['requirements_path'] = $request->file('requirements')->store('workshops/requirements', 'public');
        }

        // Acumulación controlada de evidencias de auditoría sin pisar registros anteriores
        if ($request->hasFile('images')) {
            // Como agregamos cast array al modelo, esto ya viene mapeado como array de PHP limpio
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
        // Auditoría física de archivos para no dejar basura espacial en Laragon
        if ($workshop->document_path) { Storage::disk('public')->delete($workshop->document_path); }
        if ($workshop->requirements_path) { Storage::disk('public')->delete($workshop->requirements_path); }
        
        if ($workshop->photos && is_array($workshop->photos)) {
            foreach ($workshop->photos as $photo) { 
                Storage::disk('public')->delete($photo); 
            }
        }
        
        $workshop->delete();
        return redirect()->route('workshops.index')->with('success', 'Evento eliminado de la base de datos.');
    }
}
