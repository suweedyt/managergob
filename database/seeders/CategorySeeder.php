<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Noticias',
            'type' => 'news',
            'position' => 0,
        ]);
        Category::create([
            'name' => 'Alertas',
            'type' => 'news',
            'position' => 1,
        ]);
        Category::create([
            'name' => 'Información',
            'type' => 'news',
            'position' => 2,
        ]);
    }
}
