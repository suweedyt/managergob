<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Tramite\StoreRequest;
use App\Http\Requests\Auth\Tramite\UpdateRequest;
use App\Models\Tramite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TramiteController extends Controller
{
    public function index()
    {
        $tramites = Tramite::orderBy('created_at', 'desc')->get();
        return view('auth.tramites.index', ['tramites' => $tramites]);
    }

    public function create()
    {
        return view('auth.tramites.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            if ($request->hasFile('logo_image')) {
                $file = $request->file('logo_image');
                $name = time() . '_' . preg_replace('/[^a-z0-9_\.-]/i', '_', $file->getClientOriginalName());
                $destination = public_path('images/tramites');
                if (!is_dir($destination)) mkdir($destination, 0755, true);
                $file->move($destination, $name);
                $data['logo_image'] = 'images/tramites/' . $name;
            }

            $data['is_published'] = isset($data['is_published']) ? (bool)$data['is_published'] : false;

            Tramite::create($data);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se pudo crear el trámite.'])->withInput();
        }

        session()->flash('alert-success', 'Trámite creado correctamente');
        return to_route('tramites.index');
    }

    public function edit(string $id)
    {
        $tramite = Tramite::findOrFail($id);
        return view('auth.tramites.edit', ['tramite' => $tramite]);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $tramite = Tramite::findOrFail($id);
        $data = $request->validated();

        DB::beginTransaction();
        try {
            if ($request->hasFile('logo_image')) {
                // delete old image if exists
                if ($tramite->logo_image && file_exists(public_path($tramite->logo_image))) {
                    @unlink(public_path($tramite->logo_image));
                }
                $file = $request->file('logo_image');
                $name = time() . '_' . preg_replace('/[^a-z0-9_\.-]/i', '_', $file->getClientOriginalName());
                $destination = public_path('images/tramites');
                if (!is_dir($destination)) mkdir($destination, 0755, true);
                $file->move($destination, $name);
                $data['logo_image'] = 'images/tramites/' . $name;
            }

            $data['is_published'] = isset($data['is_published']) ? (bool)$data['is_published'] : false;

            $tramite->update($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se pudo actualizar el trámite.'])->withInput();
        }

        session()->flash('alert-success', 'Trámite actualizado correctamente');
        return to_route('tramites.index');
    }

    public function destroy(string $id)
    {
        $tramite = Tramite::findOrFail($id);

        if ($tramite->logo_image && file_exists(public_path($tramite->logo_image))) {
            @unlink(public_path($tramite->logo_image));
        }

        $tramite->delete();

        session()->flash('alert-success', 'Trámite eliminado correctamente');
        return to_route('tramites.index');
    }

    public function show(string $id)
    {
        $tramite = Tramite::findOrFail($id);
        return view('auth.tramites.show', ['tramite' => $tramite]);
    }
}
