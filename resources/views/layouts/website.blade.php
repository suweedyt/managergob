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
        @endphp
        <!-- Header Start -->
        <header class="navigation" style="background-color: {{ $headerBg }}; min-height: {{ $headerHeight }}px;">
            <div class="container" style="min-height: inherit;">
                <div class="row align-items-center" style="min-height: inherit;">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg p-0" style="min-height: inherit;">
                            <a class="navbar-brand" href="/" style="height: {{ $headerHeight }}px; display: flex; align-items: center;">
                                <img src="{{ $headerLogo ? asset('images/settings/' . $headerLogo) : asset('assets/website/images/logo.svg') }}" alt="Logo" style="max-height: {{ $headerHeight - 16 }}px; width: auto;" loading="lazy">
                            </a>

                            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="ion-android-menu"></span>
                            </button>

                            <div class="collapse navbar-collapse ml-auto" id="navbarsExample09">
                                <ul class="navbar-nav ml-auto">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                                    </li>
                                    <li class="nav-item @@procedures">
                                        <a class="nav-link" href="{{ route('tramites') }}">Trámites</a>
                                    </li>
                                    <li class="nav-item @@news">
                                        <a class="nav-link" href="{{ route('news') }}">Noticias</a>
                                    </li>
                                    <li class="nav-item @@contact">
                                        <a class="nav-link" href="#">Contacto</a>
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
        <footer class="footer" style="background-color: {{ $footerBg }};">
            <div class="container py-5">
                <div class="row g-4 align-items-start">
                    <div class="col-12 col-lg-6">
                        @if ($footerContact)
                            <div class="footer-contact text-white text-left">
                                {!! nl2br(e($footerContact)) !!}
                            </div>
                        @else
                            <p class="text-white-50 mb-0">Actualiza la información de contacto desde el panel de administración.</p>
                        @endif
                    </div>
                    <div class="col-12 col-lg-6">
                        @if (!empty($footerSocials))
                            <div class="footer-socials">
                                <ul class="list-unstyled d-flex flex-wrap flex-column align-items-end gap-y-3 m-0">
                                    @foreach ($footerSocials as $social)
                                        <li>
                                            <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener" class="d-flex align-items-center gap-2 text-white">
                                                <span class="mr-2 mb-1">{{ $social['name'] ?? 'Red social' }}</span>
                                                @if (!empty($social['icon_url']))
                                                    <img src="{{ $social['icon_url'] }}" alt="Icono {{ $social['name'] ?? 'Red social' }}" style="width: 24px; height: 24px; object-fit: contain;" loading="lazy">
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
