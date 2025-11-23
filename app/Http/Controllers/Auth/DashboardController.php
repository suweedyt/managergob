<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tramite;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $postCount = Post::count();
        $categoryCount = Category::count();
        $usersCount = User::count();
        $tramiteCount = Tramite::count();

        return view('auth.dashboard', compact('usersCount', 'postCount', 'categoryCount', 'tramiteCount'));
    }
}
