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
        $type = request('type', 'news');
        $categories = Category::where('type', $type)
            ->orderBy('position')
            ->orderBy('name')
            ->get();
        return view('auth.categories.index', ['categories' => $categories, 'type' => $type]);
    }

    public function create()
    {
        $type = request('type', 'news');
        return view('auth.categories.create', ['type' => $type]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:news,tramite'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['position'] = $data['position'] ?? 0;

        Category::create($data);

        session()->flash('alert-success', 'Categoría creada correctamente');
        return to_route('categories.index', ['type' => $data['type']]);
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $type = request('type', $category->type ?? 'news');
        return view('auth.categories.edit', ['category' => $category, 'type' => $type]);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:news,tramite'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['position'] = $data['position'] ?? 0;

        $category->update($data);

        session()->flash('alert-success', 'Categoría actualizada correctamente');
        return to_route('categories.index', ['type' => $data['type']]);
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $type = $category->type ?? 'news';

        $category->delete();

        session()->flash('alert-success', 'Categoría eliminada correctamente');
        return to_route('categories.index', ['type' => $type]);
    }
}
