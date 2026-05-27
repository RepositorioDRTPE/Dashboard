<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'title'         => 'required|string|max:255',
        'description'   => 'nullable|string',
        'file'          => 'required|file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
        'published_at'  => 'required|date',
        'expired_at'    => 'required|date|after_or_equal:published_at',
        'attachments'   => 'nullable|array|max:6', // Límite estricto de 6 archivos
        'attachments.*' => 'file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
    ]);

    // 1. Guardar el archivo principal del comunicado
    $file = $request->file('file');
    $extension = strtolower($file->getClientOriginalExtension());
    $fileType = ($extension === 'pdf') ? 'pdf' : 'image';
    $mainPath = $file->store('announcements', 'public');

    // 2. Procesar los archivos adjuntos complementarios opcionales
    $attachmentsPaths = [];
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $attachedFile) {
            $attachmentsPaths[] = $attachedFile->store('announcements/attachments', 'public');
        }
    }

    // 3. Crear el registro consolidado
    \App\Models\Announcement::create([
        'title'        => $request->title,
        'description'  => $request->description,
        'file_path'    => $mainPath,
        'file_type'    => $fileType,
        'attachments'  => $attachmentsPaths, // Laravel lo parsea a JSON automáticamente por el cast
        'published_at' => $request->published_at,
        'expired_at'   => $request->expired_at,
    ]);

    return redirect()->route('announcements.index')->with('success', 'Comunicado y adjuntos publicados con éxito.');
}



    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'file'         => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
            'published_at' => 'required|date',
            'expired_at'   => 'required|date|after_or_equal:published_at',
        ]);

        $data = [
            'title'        => $request->title,
            'description'  => $request->description,
            'published_at' => $request->published_at,
            'expired_at'   => $request->expired_at,
        ];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($announcement->file_path);
            
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            
            $data['file_type'] = ($extension === 'pdf') ? 'pdf' : 'image';
            $data['file_path'] = $file->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('announcements.index')->with('success', 'Comunicado actualizado.');
    }

    public function destroy(Announcement $announcement)
    {
        Storage::disk('public')->delete($announcement->file_path);
        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Comunicado eliminado.');
    }
}

