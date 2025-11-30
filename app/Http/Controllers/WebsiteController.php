<?php

namespace App\Http\Controllers;

use App\Models\FeatureSetting;
use App\Models\Post;
use App\Models\Tramite;
use App\Models\TramiteSetting;
use App\Models\Location;
use App\Models\ContactSetting;

class WebsiteController extends Controller
{
    public function home()
    {
        $bannerSliders = Post::where('is_published', Post::Published)
            ->where('is_slider', true)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // traer algunos trámites publicados para mostrar en la home
        $tramites = Tramite::where('is_published', true)
            ->orderBy('id', 'asc')
            ->take(8)
            ->get();

        $tramiteSettings = TramiteSetting::first();
        $featureSetting = FeatureSetting::first();
        return view('website.index', ['bannerSliders' => $bannerSliders, 'tramites' => $tramites, 'tramiteSettings' => $tramiteSettings, 'featureSetting' => $featureSetting]);
    }

    public function news()
    {
        $latestsPosts = Post::where('is_published', Post::Published)
            ->where('is_news_slider', true)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
        $posts = Post::where('is_published', Post::Published)->paginate(2);
        return view('website.news.index', ['posts' => $posts, 'latestsPosts' => $latestsPosts]);
    }

    public function show(Post $new)
    {
        return view('website.news.single', ['new' => $new]);
    }

    public function tramites()
    {
        $tramites = Tramite::where('is_published', true)->orderBy('id', 'asc')->get();
        $tramiteSettings = TramiteSetting::first();
        $featureSetting = FeatureSetting::first();
        return view('website.tramites.index', ['tramites' => $tramites, 'tramiteSettings' => $tramiteSettings, 'featureSetting' => $featureSetting]);
    }

    public function tramiteShow(Tramite $tramite)
    {
        // Show the tramites index but ask it to open the selected item
        $tramites = Tramite::where('is_published', true)->orderBy('id', 'asc')->get();
        $tramiteSettings = TramiteSetting::first();
        $featureSetting = FeatureSetting::first();
        return view('website.tramites.index', ['tramites' => $tramites, 'openId' => $tramite->id, 'tramiteSettings' => $tramiteSettings]);
    }

    public function contact()
    {
        $locations = Location::where('is_published', true)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $contactSettings = ContactSetting::first();

        return view('website.locations.contact', ['locations' => $locations, 'contactSettings' => $contactSettings]);
    }
}