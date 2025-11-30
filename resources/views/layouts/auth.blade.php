<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@yield('title')</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="{{asset('assets/auth/vendors/mdi/css/materialdesignicons.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/auth/vendors/css/vendor.bundle.base.css')}}">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <!-- endinject -->
        <!-- Layout styles -->
    <!-- Font Awesome (admin) -->
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/font-awesome/css/all.min.css') }}">
        <link rel="stylesheet" href="{{asset('assets/auth/css/style.css')}}">
         <link rel="stylesheet" href="{{asset('assets/auth/css/custom-style-morena.css')}}">
        <!-- End layout styles -->
        <link rel="shortcut icon" href="{{asset('assets/auth/images/favicon.png')}}" />

        @yield('styles')

        <script src="{{ asset('assets/website/plugins/sweetalert/sweetalert.min.js') }}"></script>
    </head>
    <body>
        <div class="container-scroller">
            <!-- header admin -->
            <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo" href="{{ route('auth.dashboard') }}">
                        <img src="{{ $adminSettings && $adminSettings->logo_full ? asset('images/settings/' . $adminSettings->logo_full) : asset('assets/auth/images/logo_black.svg') }}" alt="logo" />
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="{{ route('auth.dashboard') }}">
                        <img src="{{ $adminSettings && $adminSettings->logo_mini ? asset('images/settings/' . $adminSettings->logo_mini) : asset('assets/auth/images/logo_mini.svg') }}" alt="logo" />
                    </a>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-stretch">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="nav-profile-img">
                                    <img src="{{ asset('assets/auth/images/faces/face1.jpg') }}" alt="image">
                                    <span class="availability-status online"></span>
                                </div>
                                <div class="nav-profile-text">
                                    <p class="mb-1 text-black">{{ auth()->user()->name }}</p>
                                </div>
                            </a>
                            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="#">
                                <i class="mdi mdi-settings me-2"></i> Configuración </a>
                            </div>
                        </li>
                        <li class="nav-item d-none d-lg-block full-screen-link">
                            <a class="nav-link">
                                <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                            </a>
                        </li>
                        <li class="nav-item nav-logout d-none d-lg-block">
                            <form id="logout-form" action="{{ route('logout') }}" method="post">
                                @csrf
                                <a id="logout-button" class="nav-link" href="javascript:;">
                                    <i class="mdi mdi-power"></i>
                                </a>
                            </form>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>

            <!-- container components body -->
            <div class="container-fluid page-body-wrapper">
                <!-- menu lateral -->
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        @php $isDashboard = request()->routeIs('auth.dashboard'); @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $isDashboard ? 'active' : '' }}" href="{{ route('auth.dashboard') }}">
                                <span class="menu-title">Dashboard</span>
                                <i class="mdi mdi-home menu-icon"></i>
                            </a>
                        </li>
                        @php
                            $isNoticias = request()->is('auth/posts*') || request()->is('auth/categories*') || request()->routeIs('posts.*') || request()->routeIs('categories.*');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $isNoticias ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="{{ $isNoticias ? 'true' : 'false' }}" aria-controls="ui-basic">
                                <span class="menu-title">Noticias</span>
                                <i class="menu-arrow"></i>
                                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                            </a>
                            <div class="collapse {{ $isNoticias ? 'show' : '' }}" id="ui-basic">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="{{ route('posts.index') }}">Noticias</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Categorías</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        @php
                            $isTramites = request()->routeIs('tramites.*') || request()->routeIs('tramitessettings.*');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $isTramites ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#tramites-config" aria-expanded="{{ $isTramites ? 'true' : 'false' }}" aria-controls="tramites-config">
                                <span class="menu-title">Trámites</span>
                                <i class="menu-arrow"></i>
                                <i class="mdi mdi-file-document-box menu-icon"></i>
                            </a>
                            <div class="collapse {{ $isTramites ? 'show' : '' }}" id="tramites-config">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('tramites.*') ? 'active' : '' }}" href="{{ route('tramites.index') }}">Trámites</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('tramitessettings.*') ? 'active' : '' }}" href="{{ route('tramitessettings.index') }}">Configuraciones</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        @php $isFeatureSection = request()->routeIs('featuresettings.*'); @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $isFeatureSection ? 'active' : '' }}" href="{{ route('featuresettings.index') }}">
                                <span class="menu-title">Sección destacada</span>
                                <i class="mdi mdi-star-circle menu-icon"></i>
                            </a>
                        </li>

                        @php
                            $isContacto = request()->routeIs('locations.*') || request()->routeIs('contactsettings.*');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $isContacto ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#contacto-config" aria-expanded="{{ $isContacto ? 'true' : 'false' }}" aria-controls="contacto-config">
                                <span class="menu-title">Contacto</span>
                                <i class="menu-arrow"></i>
                                <i class="mdi mdi-map-marker menu-icon"></i>
                            </a>
                            <div class="collapse {{ $isContacto ? 'show' : '' }}" id="contacto-config">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">Ubicaciones</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('contactsettings.*') ? 'active' : '' }}" href="{{ route('contactsettings.index') }}">Configuraciones</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        @php $isSiteConfig = request()->routeIs('site-settings.*'); @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $isSiteConfig ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#site-config" aria-expanded="{{ $isSiteConfig ? 'true' : 'false' }}" aria-controls="site-config">
                                <span class="menu-title">Configuración del sitio</span>
                                <i class="menu-arrow"></i>
                                <i class="mdi mdi-tune menu-icon"></i>
                            </a>
                            <div class="collapse {{ $isSiteConfig ? 'show' : '' }}" id="site-config">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('site-settings.*') ? 'active' : '' }}" href="{{ route('site-settings.index') }}">Header &amp; Footer</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        @php $isAdminConfig = request()->routeIs('admin-settings.*'); @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $isAdminConfig ? 'active' : '' }}" href="{{ route('admin-settings.index') }}">
                                <span class="menu-title">Configuración admin</span>
                                <i class="mdi mdi-tune-vertical menu-icon"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- content page -->
                <div class="main-panel">

                    @yield('section')

                </div>
            </div>
        </div>

        <!-- plugins:js -->
        <script src="{{ asset('assets/auth/vendors/js/vendor.bundle.base.js') }}"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="{{ asset('assets/auth/js/jquery.cookie.js') }}" type="text/javascript"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="{{ asset('assets/auth/js/off-canvas.js') }}"></script>
        <script src="{{ asset('assets/auth/js/hoverable-collapse.js') }}"></script>
        <script src="{{ asset('assets/auth/js/misc.js') }}"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="{{ asset('assets/auth/js/dashboard.js') }}"></script>
        <script src="{{ asset('assets/auth/js/todolist.js') }}"></script>
        <!-- End custom js for this page -->
        <!-- DataTables idioma por defecto (español) -->
        <script>
            (function setDataTablesSpanish() {
                var url = '{{ asset("assets/website/plugins/datatables/lang/es-ES.json") }}';

                function applyDefaults() {
                    if (window.jQuery && $.fn && $.fn.dataTable) {
                        $.extend(true, $.fn.dataTable.defaults, {
                            language: { url: url }
                        });
                    } else {
                        // reintentar hasta que DataTables esté disponible
                        setTimeout(applyDefaults, 60);
                    }
                }

                applyDefaults();
            })();
        </script>
        <!-- Scripts by module -->
        @yield('scripts')

        <script>
            @if (Session::has('alert-success'))
                swal("Buen Trabajo!", "{{ Session::get('alert-success') }}", "success");
            @endif
            @if (Session::has('alert-update'))
                swal("Buen Trabajo!", "{{ Session::get('alert-update') }}", "info");
            @endif
            @if (Session::has('alert-danger'))
                swal("Oops!", "{{ Session::get('alert-danger') }}", "error");
            @endif
        </script>
    </body>
</html>