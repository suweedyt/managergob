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
        ]);

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
