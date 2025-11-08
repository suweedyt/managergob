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
        <link rel="stylesheet" href="{{asset('assets/website/plugins/bootstrap/bootstrap.min.css')}}">
        <!-- Ionic Icon Css -->
        <link rel="stylesheet" href="{{asset('assets/website/plugins/Ionicons/css/ionicons.min.css')}}">
        <!-- animate.css -->
        <link rel="stylesheet" href="{{asset('assets/website/plugins/animate-css/animate.css')}}">
        <!-- Main Stylesheet -->
        <link rel="stylesheet" href="{{asset('assets/website/css/style.css')}}">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="{{asset('assets/website/css/custom-style-morena.css')}}">
        <!--styles componentes -->
        @stack('styles')
    </head>

    <body id="body">
        <!-- Header Start -->
        <header class="navigation">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg p-0">
                            <a class="navbar-brand" href="/">
                                <img src="{{ asset('assets/website/images/logo.svg') }}" alt="Logo">
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
                                        <a class="nav-link" hrf="#">Trámites</a>
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

        @yield('content')

        <!-- footer Start -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p class="copyright mb-0">
                            Copyright <script>document.write(new Date().getFullYear())</script> &copy; All rights reserved.
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