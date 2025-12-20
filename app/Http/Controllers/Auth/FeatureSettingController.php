<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FeatureSetting\StoreRequest;
use App\Models\FeatureSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeatureSettingController extends Controller
{
    public function index()
    {
        $settings = FeatureSetting::query()->first();

        return view('auth.featuresettings.index', [
            'settings' => $settings,
        ]);
    }

    public function store(StoreRequest $request)
    {
        $settings = FeatureSetting::query()->first();

        DB::transaction(function () use ($request, $settings) {
            $data = $request->validated();

            if (($data['link_type'] ?? null) === 'landing') {
                $data['button_url'] = null;
                $data['slug'] = $data['slug'] ?: Str::slug($data['title'] ?? 'destacado');
            } else {
                $data['landing_content'] = null;
                $data['slug'] = null;
                $data['landing_image'] = null;
            }

            if ($request->hasFile('background_image')) {
                $file = $request->file('background_image');
                $name = time().'_'.$file->getClientOriginalName();
                $destination = public_path('images/settings');
                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $name);
                $data['background_image'] = 'images/settings/'.$name;

                if ($settings && $settings->background_image && file_exists(public_path($settings->background_image))) {
                    @unlink(public_path($settings->background_image));
                }
            }

            if ($request->hasFile('landing_image')) {
                $file = $request->file('landing_image');
                $name = time().'_landing_'.$file->getClientOriginalName();
                $destination = public_path('images/settings');
                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $name);
                $data['landing_image'] = 'images/settings/'.$name;

                if ($settings && $settings->landing_image && file_exists(public_path($settings->landing_image))) {
                    @unlink(public_path($settings->landing_image));
                }
            }

            if ($settings) {
                $settings->update($data);
            } else {
                FeatureSetting::create($data);
            }
        });

        session()->flash('alert-success', 'Sección destacada actualizada');

        return to_route('featuresettings.index');
    }
}
