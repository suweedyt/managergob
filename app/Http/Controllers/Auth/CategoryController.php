<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('auth.categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        return view('auth.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        Category::create($data);

        session()->flash('alert-success', 'Categoría creada correctamente');
        return to_route('categories.index');
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('auth.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $category->update($data);

        session()->flash('alert-success', 'Categoría actualizada correctamente');
        return to_route('categories.index');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        // Optional: check if posts exist with this category before deleting
        $category->delete();

        session()->flash('alert-success', 'Categoría eliminada correctamente');
        return to_route('categories.index');
    }
}
