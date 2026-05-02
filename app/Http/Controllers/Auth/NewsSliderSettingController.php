<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NewsSliderSetting;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsSliderSettingController extends Controller
{
    public function index()
    {
        $settings = NewsSliderSetting::first();
        $sliderPosts = Post::with('gallery')
            ->where('is_published', Post::Published)
            ->where('is_news_slider', true)
            ->orderByDesc('id')
            ->paginate(10, ['*'], 'slider_page');

        $availablePosts = Post::with('gallery')
            ->where('is_published', Post::Published)
            ->where('is_news_slider', false)
            ->orderByDesc('id')
            ->get();

        return view('auth.newsslider.index', [
            'settings' => $settings,
            'sliderPosts' => $sliderPosts,
            'availablePosts' => $availablePosts,
        ]);
    }

    public function store(Request $request)
    {
        $addIds = collect(explode(',', (string) $request->input('add_posts')))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->all();
        $removeIds = collect(explode(',', (string) $request->input('remove_posts')))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->all();

        $request->merge([
            'add_posts' => $addIds,
            'remove_posts' => $removeIds,
        ]);

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:512'],
            'add_posts' => ['array'],
            'add_posts.*' => ['integer', 'exists:posts,id'],
            'remove_posts' => ['array'],
            'remove_posts.*' => ['integer', 'exists:posts,id'],
        ]);

        DB::transaction(function () use ($data) {
            $settings = NewsSliderSetting::first();
            if ($settings) {
                $settings->update($data);
            } else {
                NewsSliderSetting::create($data);
            }

            if (!empty($data['add_posts'])) {
                Post::whereIn('id', $data['add_posts'])->update(['is_news_slider' => true]);
            }

            if (!empty($data['remove_posts'])) {
                Post::whereIn('id', $data['remove_posts'])->update(['is_news_slider' => false]);
            }
        });

        session()->flash('alert-success', 'Configuración del slider actualizada');
        return to_route('newsslider.index');
    }
}
