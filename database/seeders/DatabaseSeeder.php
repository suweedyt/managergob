<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        SiteSetting::firstOrCreate([], [
            'header_height' => 80,
            'header_background_color' => '#ffffff',
            'footer_background_color' => '#101010',
            'footer_text_color' => '#ffffff',
            'footer_contact' => "Dirección completa\nTeléfono: (000) 000-0000\nCorreo: contacto@example.com",
            'footer_socials' => [
                [
                    'name' => 'Facebook',
                    'url' => 'https://facebook.com',
                    // example public svg icon url
                    'icon_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg',
                ],
                [
                    'name' => 'Twitter',
                    'url' => 'https://x.com',
                    'icon_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/6f/Twitter_Logo_2012.svg',
                ],
            ],
            'footer_copy' => '© '.date('Y').' Ayuntamiento. Todos los derechos reservados.',
        ]);
    }
}
