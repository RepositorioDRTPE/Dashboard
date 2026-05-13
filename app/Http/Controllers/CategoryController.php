<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('events')->orderBy('pp_code')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pp_code' => 'required|string|unique:categories',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoría creada.');
    }

    public function show(Category $category)
    {
        $category->loadCount('events');
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'pp_code' => 'required|string|unique:categories,pp_code,' . $category->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $category)
    {
        $category->delete(); // Soft delete si el modelo usa SoftDeletes
        return redirect()->route('categories.index')->with('success', 'Categoría movida a la papelera.');
    }

    // Opcionales para papelera
    public function trashed()
    {
        $categories = Category::onlyTrashed()->withCount('events')->get();
        return view('categories.trashed', compact('categories'));
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('categories.trashed')->with('success', 'Categoría restaurada.');
    }
}