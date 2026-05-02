<?php

namespace App\View\Components;

use App\Models\Post;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Slider extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $latestsPosts = Post::where('is_published', Post::Published)
            ->where('is_news_slider', true)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
        return view('components.slider', ['latestsPosts' => $latestsPosts]);
    }
}
