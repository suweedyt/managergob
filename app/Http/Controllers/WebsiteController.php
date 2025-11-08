<?php

namespace App\Http\Controllers;

use App\Models\Post;

class WebsiteController extends Controller
{
    public function home()
    {
        $bannerSliders = Post::where('is_published', Post::Published)
            ->where('is_slider', true)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('website.index', ['bannerSliders' => $bannerSliders]);
    }

    public function news()
    {
        $latestsPosts = Post::where('is_published', Post::Published)
            ->where('is_news_slider', true)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
        $posts = Post::where('is_published', Post::Published)->paginate(1);
        return view('website.news.index', ['posts' => $posts, 'latestsPosts' => $latestsPosts]);
    }

    public function show(Post $new)
    {
        return view('website.news.single', ['new' => $new]);
    }
}
