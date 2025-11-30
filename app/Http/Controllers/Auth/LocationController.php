<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\ContactSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('order', 'asc')->orderBy('id', 'desc')->get();
        return view('auth.locations.index', ['locations' => $locations]);
    }

    public function create()
    {
        $googleMapsKey = optional(ContactSetting::first())->google_maps_api_key;
        return view('auth.locations.create', ['googleMapsKey' => $googleMapsKey]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'is_published' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            Location::create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'is_published' => $request->boolean('is_published'),
                'order' => $validated['order'] ?? 0,
            ]);

            DB::commit();
            session()->flash('alert-success', 'Ubicación creada correctamente');
            return to_route('locations.index');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('alert-danger', 'Error al crear la ubicación: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit(string $id)
    {
        $location = Location::findOrFail($id);
        $googleMapsKey = optional(ContactSetting::first())->google_maps_api_key;
        return view('auth.locations.edit', ['location' => $location, 'googleMapsKey' => $googleMapsKey]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'is_published' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $location = Location::findOrFail($id);
            $location->update([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'is_published' => $request->boolean('is_published'),
                'order' => $validated['order'] ?? 0,
            ]);

            DB::commit();
            session()->flash('alert-success', 'Ubicación actualizada correctamente');
            return to_route('locations.index');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('alert-danger', 'Error al actualizar la ubicación: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $location = Location::findOrFail($id);
            $location->delete();
            session()->flash('alert-success', 'Ubicación eliminada correctamente');
        } catch (Exception $e) {
            session()->flash('alert-danger', 'Error al eliminar la ubicación: ' . $e->getMessage());
        }
        return to_route('locations.index');
    }
}