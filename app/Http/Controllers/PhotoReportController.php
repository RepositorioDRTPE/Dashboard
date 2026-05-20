<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhotoReport;
use Illuminate\Support\Facades\Storage;

class PhotoReportController extends Controller
{
    /**
     * Muestra el listado de todos los reportes fotográficos.
     */
    public function index()
    {
        // Trae los reportes ordenados del más reciente al más antiguo y los pagina de 10 en 10
        $reports = PhotoReport::latest()->paginate(10);
        
        return view('photo-reports.index', compact('reports'));
    }

    /**
     * Muestra el formulario de creación (Carga la vista create.blade.php).
     */
    public function create(Request $request)
    {
        // Captura si el usuario dio clic en 'evento' o 'difusion' en la barra lateral
        $defaultType = $request->query('type', 'evento'); 
        
        return view('photo-reports.create', compact('defaultType'));
    }

    /**
     * Almacena el reporte y las imágenes en el servidor y Base de Datos.
     */
    public function store(Request $request)
    {
        // 1. Validación estricta con mensajes personalizados en español
        $request->validate([
            'tipo_reporte' => 'required|in:evento,difusion',
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'required|string',
            'imagenes'     => 'required|array|min:1',
            'imagenes.*'   => 'image|mimes:jpeg,png,jpg,webp|max:5120', // Máximo 5MB por foto
        ], [
            'tipo_reporte.required' => 'La clasificación del reporte es obligatoria.',
            'titulo.required'       => 'El titular informativo es obligatorio.',
            'descripcion.required'  => 'La reseña o descripción es obligatoria.',
            'imagenes.required'     => 'Debe seleccionar al menos una imagen como evidencia.',
            'imagenes.*.image'      => 'El archivo seleccionado debe ser una imagen válida.',
            'imagenes.*.mimes'      => 'Solo se permiten formatos de imagen: JPG, JPEG, PNG y WEBP.',
            'imagenes.*.max'        => 'Las imágenes no deben pesar más de 5MB cada una.',
        ]);

        $rutasImagenes = [];

        // 2. Procesar y guardar los archivos en el almacenamiento
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $foto) {
                // Guarda la imagen en 'storage/app/public/photo_reports' con un nombre único aleatorio
                $path = $foto->store('photo_reports', 'public');
                $rutasImagenes[] = $path;
            }
        }

        // 3. Crear el registro en la Base de Datos
        PhotoReport::create([
            'type'        => $request->tipo_reporte,
            'title'       => $request->titulo,
            'description' => $request->descripcion,
            'photos'      => $rutasImagenes, // El modelo se encargará de pasarlo a formato JSON
        ]);

        // 4. Redireccionar al listado con un mensaje de éxito
        return redirect()->route('photo-reports.index')
            ->with('success', 'El reporte fotográfico ha sido publicado con éxito en el portal.');
    }
}

