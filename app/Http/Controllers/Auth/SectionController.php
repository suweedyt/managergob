<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Section\StoreRequest;
use App\Http\Requests\Auth\Section\UpdateRequest;
use App\Models\Section;
use Exception;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::orderBy('order')->orderByDesc('id')->get();
        return view('auth.sections.index', ['sections' => $sections]);
    }

    public function create()
    {
        return view('auth.sections.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            if ($request->hasFile('logo_image')) {
                $file = $request->file('logo_image');
                $name = time() . '_' . preg_replace('/[^a-z0-9_\.-]/i', '_', $file->getClientOriginalName());
                $destination = public_path('images/sections');
                if (!is_dir($destination)) mkdir($destination, 0755, true);
                $file->move($destination, $name);
                $data['logo_image'] = 'images/sections/' . $name;
            }

            $data['is_published'] = isset($data['is_published']) ? (bool)$data['is_published'] : false;
            $data['order'] = $data['order'] ?? 0;

            Section::create($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se pudo crear la sección.'])->withInput();
        }

        session()->flash('alert-success', 'Sección creada correctamente');
        return to_route('sections.index');
    }

    public function edit(string $id)
    {
        $section = Section::findOrFail($id);
        return view('auth.sections.edit', ['section' => $section]);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $section = Section::findOrFail($id);
        $data = $request->validated();

        DB::beginTransaction();
        try {
            if ($request->hasFile('logo_image')) {
                if ($section->logo_image && file_exists(public_path($section->logo_image))) {
                    @unlink(public_path($section->logo_image));
                }
                $file = $request->file('logo_image');
                $name = time() . '_' . preg_replace('/[^a-z0-9_\.-]/i', '_', $file->getClientOriginalName());
                $destination = public_path('images/sections');
                if (!is_dir($destination)) mkdir($destination, 0755, true);
                $file->move($destination, $name);
                $data['logo_image'] = 'images/sections/' . $name;
            }

            $data['is_published'] = isset($data['is_published']) ? (bool)$data['is_published'] : false;
            $data['order'] = $data['order'] ?? 0;

            $section->update($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se pudo actualizar la sección.'])->withInput();
        }

        session()->flash('alert-success', 'Sección actualizada correctamente');
        return to_route('sections.index');
    }

    public function destroy(string $id)
    {
        $section = Section::findOrFail($id);

        if ($section->logo_image && file_exists(public_path($section->logo_image))) {
            @unlink(public_path($section->logo_image));
        }

        $section->delete();

        session()->flash('alert-success', 'Sección eliminada correctamente');
        return to_route('sections.index');
    }
}
