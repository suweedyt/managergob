<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Post\CreateRequest;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\Post\UpdateRequest;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('category', 'gallery')->get();
       return view('auth.posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('auth.posts.create', ['categories' => $categories]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::with('gallery', 'sliderGallery')->findOrFail($id);
        $categories = Category::all();

        return view('auth.posts.edit', [
            'post' => $post,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $gallery = null;
            $sliderGallery = null;
            $sliderPositionX = $request->input('slider_position_x', 50);
            $sliderPositionY = $request->input('slider_position_y', 50);
            $bannerShortDescription = $request->boolean('is_slider') && $request->filled('banner_short_description')
                ? trim($request->input('banner_short_description'))
                : null;

            if ($request->has('file')) {
                $file = $request->file;
                $fileName = time() . $file->getClientOriginalName();
                $imagePath = public_path('images/posts');
                $file->move($imagePath, $fileName);

                $gallery = Gallery::create([
                    'image' => $fileName
                ]);
            }

            if ($request->boolean('is_slider') && $request->boolean('banner_use_different') && $request->hasFile('slider_file')) {
                $sfile = $request->file('slider_file');
                $sFileName = time() . '_slider_' . $sfile->getClientOriginalName();
                $sliderPath = public_path('images/posts');
                $sfile->move($sliderPath, $sFileName);

                $sliderGallery = Gallery::create([
                    'image' => $sFileName
                ]);
            }

            $sliderGalleryId = null;
            if ($request->boolean('is_slider')) {
                if ($request->boolean('banner_use_different')) {
                    $sliderGalleryId = $sliderGallery?->id;
                } else {
                    $sliderGalleryId = $gallery?->id;
                }
            } elseif ($request->boolean('is_news_slider')) {
                // Si la noticia está marcada solo como noticia para el slider del home (is_news_slider)
                // pero no como banner, usar la galería principal como imagen del slider.
                $sliderGalleryId = $gallery?->id;
            }

            Post::create([
                'category_id' => $request->category,
                'is_published' => $request->is_published,
                'title' => $request->title,
                'description' => $request->description,
                'gallery_id' => $gallery?->id,
                'is_slider' => $request->boolean('is_slider'),
                'is_news_slider' => $request->boolean('is_news_slider'),
                'slider_gallery_id' => $sliderGalleryId,
                // Asegurar posiciones si la noticia será usada en un slider (banner o news slider)
                'slider_position_x' => ($request->boolean('is_slider') || $request->boolean('is_news_slider')) ? $sliderPositionX : null,
                'slider_position_y' => ($request->boolean('is_slider') || $request->boolean('is_news_slider')) ? $sliderPositionY : null,
                'banner_short_description' => $bannerShortDescription,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }

        session()->flash('alert-success', 'Noticia creada correctamente');
        return to_route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Preview a post as it would appear on the public site.
     * Returns an HTML partial suitable for injecting into a modal (via AJAX).
     */
    public function preview(string $id)
    {
        $post = Post::with('gallery', 'sliderGallery', 'category')->findOrFail($id);

        // Return only the partial HTML fragment for injection into admin list
        return view('auth.posts.preview', ['post' => $post]);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        // validated by UpdateRequest

        try {
            DB::beginTransaction();

            $post = Post::findOrFail($id);

            $bannerShortDescription = $request->boolean('is_slider') && $request->filled('banner_short_description')
                ? trim($request->input('banner_short_description'))
                : null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . $file->getClientOriginalName();
                $imagePath = public_path('images/posts');
                $file->move($imagePath, $fileName);

                $newGallery = Gallery::create(['image' => $fileName]);

                $post->gallery_id = $newGallery->id;
            }

            if ($request->boolean('is_slider')) {
                $post->is_slider = true;

                $bannerUseDifferent = $request->boolean('banner_use_different');

                if ($request->hasFile('slider_file')) {
                    if ($post->sliderGallery && ($post->sliderGallery->id !== ($post->gallery_id ?? null))) {
                        $oldImage = $post->sliderGallery->image;
                        $oldPath = public_path('images/posts/' . $oldImage);
                        if ($oldImage && file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                        $post->sliderGallery->delete();
                    }

                    $sfile = $request->file('slider_file');
                    $sFileName = time() . '_slider_' . $sfile->getClientOriginalName();
                    $sliderPath = public_path('images/posts');
                    $sfile->move($sliderPath, $sFileName);

                    $newSliderGallery = Gallery::create(['image' => $sFileName]);
                    $post->slider_gallery_id = $newSliderGallery->id;

                } else {
                    if ($bannerUseDifferent) {
                        if (!($post->slider_gallery_id && $post->slider_gallery_id != ($post->gallery_id ?? null))) {
                            $post->slider_gallery_id = null;
                        }
                    } else {
                        if ($post->sliderGallery && ($post->sliderGallery->id !== ($post->gallery_id ?? null))) {
                            $oldImage = $post->sliderGallery->image;
                            $oldPath = public_path('images/posts/' . $oldImage);
                            if ($oldImage && file_exists($oldPath)) {
                                @unlink($oldPath);
                            }
                            $post->sliderGallery->delete();
                        }

                        $post->slider_gallery_id = $post->gallery_id ?? null;
                    }
                }

                $post->slider_position_x = $request->input('slider_position_x', $post->slider_position_x ?? 50);
                $post->slider_position_y = $request->input('slider_position_y', $post->slider_position_y ?? 50);
            } else {
                $post->is_slider = false;

                if ($request->boolean('is_news_slider')) {
                    // No es banner pero sí aparece en el slider de noticias: mantener o establecer posiciones
                    $post->slider_position_x = $request->input('slider_position_x', $post->slider_position_x ?? 50);
                    $post->slider_position_y = $request->input('slider_position_y', $post->slider_position_y ?? 50);
                    $post->slider_gallery_id = $post->gallery_id ?? null;
                } else {
                    $post->slider_position_x = null;
                    $post->slider_position_y = null;
                    $post->slider_gallery_id = null;
                }

                if ($post->sliderGallery && ($post->sliderGallery->id !== ($post->gallery_id ?? null))) {
                    $oldImage = $post->sliderGallery->image;
                    $oldPath = public_path('images/posts/' . $oldImage);
                    if ($oldImage && file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                    $post->sliderGallery->delete();
                }
            }

            $post->is_news_slider = $request->boolean('is_news_slider');

            $post->title = $request->title;
            $post->category_id = $request->category;
            $post->is_published = $request->is_published;
            $post->description = $request->description;
            $post->banner_short_description = $bannerShortDescription;

            $post->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }

        session()->flash('alert-success', 'Noticia actualizada correctamente');
        return to_route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $post = Post::with('gallery', 'sliderGallery')->findOrFail($id);

            // delete main gallery file and record if exists
            if ($post->gallery) {
                $image = $post->gallery->image;
                $path = public_path('images/posts/' . $image);
                if ($image && file_exists($path)) {
                    @unlink($path);
                }
                // delete gallery record
                $post->gallery->delete();
            }

            // delete slider gallery file and record if exists
            if ($post->sliderGallery) {
                $simage = $post->sliderGallery->image;
                $spath = public_path('images/posts/' . $simage);
                if ($simage && file_exists($spath)) {
                    @unlink($spath);
                }
                $post->sliderGallery->delete();
            }

            // finally delete the post
            $post->delete();

            DB::commit();

            return response()->json(['message' => 'deleted'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}