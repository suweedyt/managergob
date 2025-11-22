<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::query()->first();

        return view('auth.site-settings.index', [
            'settings' => $settings,
        ]);
    }

    public function store(SiteSettingRequest $request)
    {
        $settings = SiteSetting::query()->first();

        DB::transaction(function () use ($request, $settings) {
            $data = $request->validated();

            if ($request->hasFile('header_logo')) {
                $file = $request->file('header_logo');
                $fileName = time().'_'.$file->getClientOriginalName();
                $destination = public_path('images/settings');
                $file->move($destination, $fileName);
                $data['header_logo'] = $fileName;
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
