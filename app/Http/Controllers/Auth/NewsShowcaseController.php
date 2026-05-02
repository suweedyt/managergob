<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NewsShowcaseItem;
use App\Models\Post;
use App\Models\NewsShowcaseSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsShowcaseController extends Controller
{
    public function index()
    {
        $items = NewsShowcaseItem::with('post.gallery')
            ->orderByDesc('id')
            ->paginate(10);

        $availablePosts = Post::with('gallery')
            ->where('is_published', Post::Published)
            ->whereDoesntHave('newsShowcaseItem')
            ->orderByDesc('id')
            ->get();

        $settings = NewsShowcaseSetting::first();

        return view('auth.newsshowcase.index', [
            'items' => $items,
            'availablePosts' => $availablePosts,
            'settings' => $settings,
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
            // Save settings
            $settings = NewsShowcaseSetting::first();
            $settingsData = [
                'title' => $data['title'] ?? null,
                'subtitle' => $data['subtitle'] ?? null,
            ];
            if ($settings) {
                $settings->update($settingsData);
            } else {
                NewsShowcaseSetting::create($settingsData);
            }

            if (!empty($data['add_posts'])) {
                $payload = collect($data['add_posts'])
                    ->unique()
                    ->map(fn ($id) => ['post_id' => $id, 'created_at' => now(), 'updated_at' => now()])
                    ->values()
                    ->all();
                NewsShowcaseItem::insert($payload);
            }

            if (!empty($data['remove_posts'])) {
                NewsShowcaseItem::whereIn('post_id', $data['remove_posts'])->delete();
            }
        });

        session()->flash('alert-success', 'Aparador de noticias actualizado');
        return to_route('newsshowcase.index');
    }

    public function toggleSize(string $id)
    {
        $item = NewsShowcaseItem::findOrFail($id);
        $item->is_large = ! (bool) $item->is_large;
        $item->save();

        return response()->json(['ok' => true, 'is_large' => (bool) $item->is_large]);
    }
}