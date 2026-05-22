<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BulletinController extends Controller
{
    public function index()
    {
        $bulletins = Bulletin::latest()->paginate(10);
        return view('bulletins.index', compact('bulletins'));
    }

    public function create()
    {
        return view('bulletins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'required|file|mimes:pdf|max:15360', // Máximo 15MB
        ], [
            'title.required' => 'El título del boletín es obligatorio.',
            'file.required'  => 'Debe cargar un documento PDF.',
            'file.mimes'     => 'El archivo debe ser estrictamente formato PDF.'
        ]);

        $path = $request->file('file')->store('bulletins', 'public');

        Bulletin::create([
            'title'       => $request->title,
            'description' => $request->description,
            'file_path'   => $path,
        ]);

        return redirect()->route('bulletins.index')->with('success', 'Boletín publicado correctamente.');
    }

    public function edit(Bulletin $bulletin)
    {
        return view('bulletins.edit', compact('bulletin'));
    }

    public function update(Request $request, Bulletin $bulletin)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:pdf|max:15360',
        ]);

        $data = [
            'title'       => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('file')) {
            // Eliminar el archivo PDF anterior del disco
            Storage::disk('public')->delete($bulletin->file_path);
            $data['file_path'] = $request->file('file')->store('bulletins', 'public');
        }

        $bulletin->update($data);

        return redirect()->route('bulletins.index')->with('success', 'Boletín actualizado con éxito.');
    }

    public function destroy(Bulletin $bulletin)
    {
        // Borrar el archivo PDF del servidor antes de borrar el registro
        Storage::disk('public')->delete($bulletin->file_path);
        $bulletin->delete();

        return redirect()->route('bulletins.index')->with('success', 'Boletín eliminado definitivamente.');
    }
}

