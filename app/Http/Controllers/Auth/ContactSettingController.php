<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactSetting;

class ContactSettingController extends Controller
{
    public function index()
    {
        $settings = ContactSetting::first();
        return view('auth.contactsettings.index', ['contactsettings' => $settings]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:512'],
            'google_maps_api_key' => ['nullable', 'string', 'max:255'],
        ]);

        $settings = ContactSetting::first();
        if ($settings) {
            $settings->update($data);
        } else {
            ContactSetting::create($data);
        }

        session()->flash('alert-success', 'Configuración de contacto actualizada');
        return to_route('contactsettings.index');
    }
}
