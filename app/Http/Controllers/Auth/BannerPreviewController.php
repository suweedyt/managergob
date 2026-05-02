<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BannerPreviewController extends Controller
{
    public function show(Request $request)
    {
        $mediaType = $request->query('media_type');
        $mediaPath = $request->query('media_path');
        $positionX = $request->query('position_x', 50);
        $positionY = $request->query('position_y', 50);
        $title = $request->query('title', 'Preview');

        return view('auth.banners.preview', compact('mediaType', 'mediaPath', 'positionX', 'positionY', 'title'));
    }
}
