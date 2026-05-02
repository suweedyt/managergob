<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SectionSetting;
use Illuminate\Http\Request;

class SectionSettingController extends Controller
{
    public function index()
    {
        $settings = SectionSetting::first();
        return view('auth.sectionssettings.index', ['sectionssettings' => $settings]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:512'],
        ]);

        $settings = SectionSetting::first();
        if ($settings) {
            $settings->update($data);
        } else {
            SectionSetting::create($data);
        }

        session()->flash('alert-success', 'Configuración de secciones actualizada');
        return to_route('sectionssettings.index');
    }
}
