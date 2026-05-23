<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')
            ->withCount('components')
            ->latest()
            ->paginate(10);

        return view('categorias.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('categorias.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($request->only('name', 'parent_id'));

        return redirect()->route('categorias.index')->with('success', 'Categoría creada con éxito.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $categoria)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $categoria->id)
            ->get();
        return view('categorias.edit', ['category' => $categoria, 'parentCategories' => $parentCategories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $categoria)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $categoria->id,
            'parent_id' => 'nullable|exists:categories,id|different:id',
        ]);

        $categoria->update($request->only('name', 'parent_id'));

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $categoria)
    {
        // Desasociar componentes antes de borrar o proteger si tiene componentes
        if ($categoria->components()->exists()) {
            return redirect()->route('categorias.index')->with('error', 'No se puede eliminar la categoría porque tiene componentes asociados.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada con éxito.');
    }
}
