<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\SubEvent;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function index()
    {
        // Verificamos que sea Admin (Opcional, pero recomendado según tus reglas)
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para ver la papelera.');
        }

        // Traemos todo lo que ha sido eliminado (SoftDeletes)
        $deletedCategories = Category::onlyTrashed()->get();
        $deletedEvents = Event::onlyTrashed()->with('category')->get();
        $deletedSubEvents = SubEvent::onlyTrashed()->with('event')->get();

        return view('admin.trash', compact('deletedCategories', 'deletedEvents', 'deletedSubEvents'));
    }

    public function restore(Request $request, $tipo, $id)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        switch ($tipo) {
            case 'category':
                Category::onlyTrashed()->findOrFail($id)->restore();
                $mensaje = 'Actividad General (PP) restaurada.';
                break;
            case 'event':
                Event::onlyTrashed()->findOrFail($id)->restore();
                $mensaje = 'Actividad Operativa restaurada.';
                break;
            case 'subevent':
                SubEvent::onlyTrashed()->findOrFail($id)->restore();
                $mensaje = 'Reporte de avance y sus fotos restaurados.';
                break;
            default:
                return back()->with('error', 'Tipo de registro no válido.');
        }

        return back()->with('success', $mensaje);
    }
}
