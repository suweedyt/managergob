<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SiteSettingRequest;
use App\Models\SiteSetting;
use App\Models\ContactSetting;
use Illuminate\Support\Facades\DB;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::query()->first();
        $contactSettings = ContactSetting::query()->first();

        return view('auth.site-settings.index', [
            'settings' => $settings,
            'contactSettings' => $contactSettings,
        ]);
    }

    public function store(SiteSettingRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('header_logo') && !$request->file('header_logo')->isValid()) {
            return back()->withErrors([
                'header_logo' => 'El archivo del logo de encabezado es inválido',
            ])->withInput();
        }

        if ($request->hasFile('footer_logo') && !$request->file('footer_logo')->isValid()) {
            return back()->withErrors([
                'footer_logo' => 'El archivo del logo del footer es inválido',
            ])->withInput();
        }

        $settings = SiteSetting::query()->first();

        DB::transaction(function () use (&$data, $request, $settings) {

            if ($request->hasFile('header_logo')) {
                $file = $request->file('header_logo');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->storeAs('images/settings', $fileName, 'public');

                $data['header_logo'] = $fileName;
            }

            if ($request->hasFile('footer_logo')) {
                $file = $request->file('footer_logo');
                $fileName = time() . '_footer_' . $file->getClientOriginalName();

                $file->storeAs('images/settings', $fileName, 'public');

                $data['footer_logo'] = $fileName;
            }

            $data['footer_text_color'] = $data['footer_text_color'] ?? '#ffffff';

            if (isset($data['footer_socials'])) {
                $data['footer_socials'] = array_values(array_filter(array_map(function ($item) {
                    $name = trim($item['name'] ?? '');
                    $url = trim($item['url'] ?? '');
                    $iconUrl = trim($item['icon_url'] ?? '');

                    if ($name === '' && $url === '' && $iconUrl === '') {
                        return null;
                    }

                    return [
                        'name' => $name,
                        'url' => $url,
                        'icon_url' => $iconUrl,
                    ];
                }, $data['footer_socials'])));
            }

            if (isset($data['footer_links'])) {
                $data['footer_links'] = array_values(array_filter(array_map(function ($item) {
                    $name = trim($item['name'] ?? '');
                    $url = trim($item['url'] ?? '');

                    if ($name === '' && $url === '') {
                        return null;
                    }

                    return [
                        'name' => $name,
                        'url' => $url,
                    ];
                }, $data['footer_links'])));
            }

            if ($settings) {
                $settings->update($data);
            } else {
                SiteSetting::create($data);
            }
        });

        session()->flash('alert-success', 'Configuraciones actualizadas correctamente');

        return to_route('site-settings.index');
    }
}
