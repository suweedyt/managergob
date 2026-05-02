<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Banner\StoreRequest;
use App\Http\Requests\Auth\Banner\UpdateRequest;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderByDesc('id')->get();

        return view('auth.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('auth.banners.create');
    }

    public function store(StoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            // support either uploaded files or external URLs
            if ($request->input('media_source') === 'url' && $request->filled('media_url')) {
                $data['media_path'] = $request->input('media_url');
            } elseif ($request->hasFile('media')) {
                $file = $request->file('media');
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('images/banners');
                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $data['media_path'] = $filename;
            }

            Banner::create($data);
        });

        session()->flash('alert-success', 'Banner creado correctamente');

        return to_route('banners.index');
    }

    public function show(Banner $banner)
    {
        $mediaType = $banner->media_type;
        $mediaPath = $banner->media_path;
        $positionX = $banner->position_x ?? 50;
        $positionY = $banner->position_y ?? 50;
        $title = $banner->title;

        return view('auth.banners.preview', compact('mediaType', 'mediaPath', 'positionX', 'positionY', 'title'));
    }

    public function edit(Banner $banner)
    {
        return view('auth.banners.edit', compact('banner'));
    }

    public function update(UpdateRequest $request, Banner $banner)
    {
        DB::transaction(function () use ($request, $banner) {
            $data = $request->validated();

            // If new media provided as URL
            if ($request->input('media_source') === 'url' && $request->filled('media_url')) {
                // if previous media was a local file, delete it
                if ($banner->media_path && !Str::startsWith($banner->media_path, ['http://','https://']) && file_exists(public_path('images/banners/' . ltrim($banner->media_path, '/')))) {
                    @unlink(public_path('images/banners/' . ltrim($banner->media_path, '/')));
                }
                $data['media_path'] = $request->input('media_url');
            } elseif ($request->hasFile('media')) {
                if ($banner->media_path && !Str::startsWith($banner->media_path, ['http://','https://']) && file_exists(public_path('images/banners/' . ltrim($banner->media_path, '/')))) {
                    @unlink(public_path('images/banners/' . ltrim($banner->media_path, '/')));
                }

                $file = $request->file('media');
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('images/banners');
                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $data['media_path'] = $filename;
            }

            $banner->update($data);
        });

        session()->flash('alert-update', 'Banner actualizado correctamente');

        return to_route('banners.index');
    }

    public function destroy(Banner $banner)
    {
        DB::transaction(function () use ($banner) {
            if ($banner->media_path && file_exists(public_path('images/banners/' . ltrim($banner->media_path, '/')))) {
                @unlink(public_path('images/banners/' . ltrim($banner->media_path, '/')));
            }

            $banner->delete();
        });

        return response()->json(['message' => 'deleted'], 200);
    }
}
