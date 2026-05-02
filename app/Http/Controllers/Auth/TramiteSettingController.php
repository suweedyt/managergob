<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TramiteSetting;

class TramiteSettingController extends Controller
{
    public function index()
    {
        $settings = TramiteSetting::first();
        return view('auth.tramitessettings.index', ['tramitessettings' => $settings]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:512'],
            'logo_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // max 5MB
        ]);

        // handle logo upload
        if ($request->hasFile('logo_image')) {
            $file = $request->file('logo_image');
            $filename = time() . '_tramites_logo.' . $file->getClientOriginalExtension();
            $destination = public_path('images/tramites');
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $data['logo_image'] = 'images/tramites/' . $filename;
        }

        $settings = TramiteSetting::first();
        if ($settings) {
            $settings->update($data);
        } else {
            TramiteSetting::create($data);
        }

        session()->flash('alert-success', 'Configuración de trámites actualizada');
        return to_route('tramitessettings.index');
    }
}
