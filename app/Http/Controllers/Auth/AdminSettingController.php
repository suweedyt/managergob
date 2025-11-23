<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminSettingRequest;
use App\Models\AdminSetting;
use Illuminate\Support\Facades\DB;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = AdminSetting::query()->first();

        return view('auth.admin-settings.index', [
            'settings' => $settings,
        ]);
    }

    public function store(AdminSettingRequest $request)
    {
        $settings = AdminSetting::query()->first();

        DB::transaction(function () use ($request, $settings) {
            $data = [];
            $destination = public_path('images/settings');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            if ($request->hasFile('logo_full')) {
                $file = $request->file('logo_full');
                $fileName = time().'_full_'.$file->getClientOriginalName();
                $file->move($destination, $fileName);
                $data['logo_full'] = $fileName;

                if ($settings && $settings->logo_full) {
                    $oldPath = $destination.'/'.$settings->logo_full;
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
            }

            if ($request->hasFile('logo_mini')) {
                $file = $request->file('logo_mini');
                $fileName = time().'_mini_'.$file->getClientOriginalName();
                $file->move($destination, $fileName);
                $data['logo_mini'] = $fileName;

                if ($settings && $settings->logo_mini) {
                    $oldPath = $destination.'/'.$settings->logo_mini;
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
            }

            if ($settings) {
                $settings->update($data);
            } elseif (!empty($data)) {
                AdminSetting::create($data);
            }
        });

        session()->flash('alert-success', 'Configuración de administrador actualizada correctamente');

        return to_route('admin-settings.index');
    }
}
