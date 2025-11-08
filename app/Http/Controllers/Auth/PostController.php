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

            if ($request->has('file')) {
                $file = $request->file;
                $fileName = time() . $file->getClientOriginalName();
                $imagePath = public_path('images/posts');
                $file->move($imagePath, $fileName);

                $gallery = Gallery::create([
                    'image' => $fileName
                ]);
            }

            // Handle slider image if checkbox is set
            if ($request->boolean('is_slider') && $request->hasFile('slider_file')) {
                $sfile = $request->file('slider_file');
                $sFileName = time() . '_slider_' . $sfile->getClientOriginalName();
                $sliderPath = public_path('images/posts');
                $sfile->move($sliderPath, $sFileName);

                $sliderGallery = Gallery::create([
                    'image' => $sFileName
                ]);
            }

            Post::create([
                'category_id' => $request->category,
                'is_published' => $request->is_published,
                'title' => $request->title,
                'description' => $request->description,
                'gallery_id' => $gallery?->id,
                'is_slider' => $request->boolean('is_slider'),
                'is_news_slider' => $request->boolean('is_news_slider'),
                'slider_gallery_id' => $sliderGallery?->id,
                'slider_position_x' => $sliderPositionX,
                'slider_position_y' => $sliderPositionY,
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

            // Handle main image replacement
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . $file->getClientOriginalName();
                $imagePath = public_path('images/posts');
                $file->move($imagePath, $fileName);

                // create new gallery record
                $newGallery = Gallery::create(['image' => $fileName]);

                // update post gallery_id
                $post->gallery_id = $newGallery->id;
            }

            // Handle slider image
            if ($request->boolean('is_slider')) {
                if ($request->hasFile('slider_file')) {
                    $sfile = $request->file('slider_file');
                    $sFileName = time() . '_slider_' . $sfile->getClientOriginalName();
                    $sliderPath = public_path('images/posts');
                    $sfile->move($sliderPath, $sFileName);

                    $newSliderGallery = Gallery::create(['image' => $sFileName]);
                    $post->slider_gallery_id = $newSliderGallery->id;
                    $post->is_slider = true;
                } else {
                    // only enable slider flag
                    $post->is_slider = true;
                }

                // Save slider coordinates (default to existing values or 50 if not set)
                $post->slider_position_x = $request->input('slider_position_x', $post->slider_position_x ?? 50);
                $post->slider_position_y = $request->input('slider_position_y', $post->slider_position_y ?? 50);
            } else {
                // if unchecked, disable slider and keep slider_gallery_id or set null
                $post->is_slider = false;
                // optional: keep existing slider_gallery_id or set to null
                // $post->slider_gallery_id = null;
            }

            $post->is_news_slider = $request->boolean('is_news_slider');

            $post->title = $request->title;
            $post->category_id = $request->category;
            $post->is_published = $request->is_published;
            $post->description = $request->description;

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
