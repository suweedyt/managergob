<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Basic Page Needs
        ================================================== -->
        <meta charset="utf-8">
        <title>@yield('title')</title>

        <!-- Mobile Specific Metas
        ================================================== -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Página Gobierno">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
        <meta name="author" content="Suweed">
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/website/images/favicon.png') }}" />
        <!-- bootstrap.min css -->
        <link rel="stylesheet" href="{{ asset('assets/website/plugins/bootstrap/bootstrap.min.css') }}">
        <!-- Ionic Icon Css -->
        <link rel="stylesheet" href="{{ asset('assets/website/plugins/Ionicons/css/ionicons.min.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('assets/website/plugins/font-awesome/css/all.min.css') }}">
        <!-- animate.css -->
        <link rel="stylesheet" href="{{ asset('assets/website/plugins/animate-css/animate.css') }}">
        <!-- Main Stylesheet -->
        <link rel="stylesheet" href="{{ asset('assets/website/css/style.css') }}">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="{{ asset('assets/website/css/custom-style-morena.css') }}">
        <!--styles componentes -->
        @stack('styles')
    </head>

    <body id="body" class="site-flex">
        @php
            $headerHeight = optional($siteSettings)->header_height ?? 80;
            $headerBg = optional($siteSettings)->header_background_color ?? '#ffffff';
            $headerLogo = optional($siteSettings)->header_logo;
            $footerCopy = optional($siteSettings)->footer_copy;
            $footerBg = optional($siteSettings)->footer_background_color ?? '#101010';
            $footerContact = optional($siteSettings)->footer_contact;
            $footerSocials = optional($siteSettings)->footer_socials ?? [];
            $footerLinks = optional($siteSettings)->footer_links ?? [];
            $footerLogo = optional($siteSettings)->footer_logo;
            $footerMap = optional($siteSettings)->footer_map_iframe;
        @endphp
        
        <!-- Header Start -->
        <header class="navigation" style="background-color: {{ $headerBg }}; min-height: {{ $headerHeight }}px;">
            <div class="container" style="min-height: inherit;">
                <div class="row align-items-center" style="min-height: inherit;">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg p-0" style="min-height: inherit;">
                            <a class="navbar-brand" href="/" style="height: {{ $headerHeight }}px; display: flex; align-items: center;">
                                <img src="{{ $headerLogo ? asset('storage/images/settings/' . $headerLogo) : asset('assets/website/images/logo.svg') }}" alt="Logo" style="max-height: {{ $headerHeight - 16 }}px; width: auto;" loading="lazy">
                            </a>

                            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="ion-android-menu"></span>
                            </button>

                            <div class="collapse navbar-collapse ml-auto" id="navbarsExample09">
                                @php
                                    $isHome = request()->routeIs('home') || request()->is('/');
                                    $isTramites = request()->routeIs('tramites*') || request()->is('tramites*');
                                    $isNews = request()->routeIs('news*') || request()->is('news*');
                                    $isContact = request()->routeIs('contact*') || request()->is('contact*');
                                @endphp

                                <ul class="navbar-nav ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $isHome ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $isTramites ? 'active' : '' }}" href="{{ route('tramites') }}">Trámites</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $isNews ? 'active' : '' }}" href="{{ route('news') }}">Noticias</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $isContact ? 'active' : '' }}" href="{{ route('contact') }}">Contacto</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header><!-- header close -->

        <main class="site-content">
            @yield('content')
        </main>

        <!-- footer Start -->
        <footer class="footer" style="background-color: {{ $footerBg }}; color: {{ optional($siteSettings)->footer_text_color ?? '#ffffff' }};">
            <div class="container py-5">
                <style>
                    /* Footer local adjustments: center blocks; for social links place icon at the right and text at the left */
                    .footer .footer-links-list,
                    .footer .footer-socials-list {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        gap: .5rem;
                        margin: 0;
                        padding: 0;
                    }
                    .footer .footer-social-link {
                        display: flex;
                        align-items: center;
                        gap: .5rem;
                        width: 220px; /* keep a consistent column width */
                        justify-content: flex-end; /* align content to the right side of the block */
                        color: inherit;
                        text-decoration: none;
                    }
                    /* ensure text sits immediately to the left of the icon */
                    .footer .footer-social-link span { order: 1; margin-right: .5rem; }
                    .footer .footer-social-link img { order: 2; width: 24px; height: 24px; object-fit: contain; }
                    .footer .footer-links-list a { display: block; width: 220px; text-align: left; color: inherit; text-decoration: none; }
                    @media (max-width: 767px) {
                        .footer .footer-social-link,
                        .footer .footer-links-list a { width: 100%; text-align: left; }
                    }
                </style>
                <div class="row g-4 align-items-start">
                    <div class="col-12 col-md-4 col-lg-2 d-flex flex-column gap-3">
                        @if(optional($siteSettings)->footer_logo)
                            <img src="{{ asset('storage/images/settings/' . $siteSettings->footer_logo) }}" alt="Logo footer" class="img-fluid" style="max-height: 140px; object-fit: contain;">
                        @else
                            <img src="{{ asset('assets/website/images/logo.svg') }}" alt="Logo" class="img-fluid" style="max-height: 140px; object-fit: contain;">
                        @endif
                    </div>

                    <div class="col-12 col-md-4 col-lg-3">
                        <h6 class="text-uppercase text-white-50 mb-3">Dirección</h6>
                        @if ($footerContact)
                            <div class="footer-contact text-white">
                                {!! nl2br(e($footerContact)) !!}
                            </div>
                        @else
                            <p class="text-white-50 mb-0">Actualiza la información de contacto desde el panel de administración.</p>
                        @endif
                    </div>

                    <div class="col-12 col-md-4 col-lg-3">
                        @php
                            $mapSrc = optional($siteSettings)->footer_map_iframe;
                            $mapHref = $mapSrc;
                            if ($mapSrc) {
                                try {
                                    $u = parse_url($mapSrc);
                                    parse_str($u['query'] ?? '', $qs);
                                    $lat = $lng = null;
                                    $zoom = $qs['zoom'] ?? null;

                                    if (!empty($qs['center'])) {
                                        [$lat, $lng] = explode(',', $qs['center']) + [null, null];
                                    } elseif (!empty($qs['markers'])) {
                                        if (preg_match('/(-?\d+\.\d+),\s*(-?\d+\.\d+)/', $qs['markers'], $m)) {
                                            $lat = $m[1]; $lng = $m[2];
                                        }
                                    } else {
                                        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+),(\d+)z/', $mapSrc, $m)) {
                                            $lat = $m[1]; $lng = $m[2]; $zoom = $m[3];
                                        }
                                    }

                                    if ($lat !== null && $lng !== null) {
                                        $z = is_numeric($zoom) ? intval($zoom) : 15;
                                        // formato requerido: https://www.google.com/maps?q=lat,lng&z=18
                                        $mapHref = "https://www.google.com/maps?q={$lat},{$lng}&z={$z}";
                                   }
                                } catch (\Throwable $e) {
                                    // keep fallback
                                }
                            }
                        @endphp

                        @if ($mapSrc)
                            <div class="ratio ratio-4x3">
                                <a href="{{ $mapHref }}" target="_blank" rel="noopener">
                                    <img src="{!! $mapSrc !!}" alt="Mapa del sitio" class="img-fluid">
                                </a>
                            </div>
                        @else
                            <p class="text-white-50 mb-0">Agrega el iframe del mapa en la configuración.</p>
                        @endif
                    </div>

                    <div class="col-12 col-md-6 col-lg-2 footer-links">
                        <h6 class="text-uppercase text-white-50 mb-3">Enlaces de interés</h6>
                        @php $footerLinks = optional($siteSettings)->footer_links ?? []; @endphp
                        @if (!empty($footerLinks))
                            <ul class="list-unstyled mb-0 footer-links-list">
                                @foreach ($footerLinks as $link)
                                    <li class="mb-2">
                                        <a href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener">{{ $link['name'] ?? 'Enlace' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-white-50 mb-0">Agrega enlaces desde el panel.</p>
                        @endif
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <h6 class="text-uppercase text-white-50 mb-3">Redes sociales</h6>
                        @if (!empty($footerSocials))
                            <div class="footer-socials">
                                <ul class="list-unstyled m-0 footer-socials-list">
                                    @foreach ($footerSocials as $social)
                                        <li>
                                            <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener" class="footer-social-link">
                                                <span>{{ $social['name'] ?? 'Red social' }}</span>
                                                @if (!empty($social['icon_url']))
                                                    <img src="{{ $social['icon_url'] }}" alt="Icono {{ $social['name'] ?? 'Red social' }}" loading="lazy">
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="text-white-50 mb-0">Agrega redes sociales desde el panel de administración.</p>
                        @endif
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <hr class="border-secondary opacity-25">
                        <p class="mb-0 text-white-50 text-center">
                            {{ $footerCopy ?? 'Copyright '.date('Y').' © All rights reserved.' }}
                        </p>
                    </div>
                </div>
            </div>
        </footer>

        <!--Scroll to top-->
        <div id="scroll-to-top" class="scroll-to-top">
            <span class="icon ion-ios-arrow-up"></span>
        </div>

        <!--
        Essential Scripts
        =====================================-->

        <!-- Main jQuery -->
        <script src="{{ asset('assets/website/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4.5 -->
        <script src="{{ asset('assets/website/plugins/bootstrap/bootstrap.min.js') }}"></script>
        <!-- scripts custom -->
        <script src="{{ asset('assets/website/js/script.js') }}"></script>
         <!-- scripts componentes -->
        @stack('scripts')
    </body>
</html>
