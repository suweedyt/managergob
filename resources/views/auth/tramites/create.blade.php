@extends('layouts.auth')

@section('title', 'Crear Trámite')

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Trámites </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('tramites.index') }}">Trámites</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crear Trámite</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="header-subsection">
                            <h4 class="card-title">Crear Trámite</h4>
                            <div>
                                <a href="{{ route('tramites.index') }}" id="btn-back-to-list" class="btn btn-sm btn-outline-secondary">← Volver a la lista</a>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('tramites.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="mode" id="mode" value="content">

                            <div class="accordion" id="tramiteFullAccordion">
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="tramiteGeneralHeading">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#tramiteGeneral" aria-expanded="true" aria-controls="tramiteGeneral">
                                            General
                                        </button>
                                    </h2>
                                    <div id="tramiteGeneral" class="accordion-collapse collapse show" aria-labelledby="tramiteGeneralHeading" data-bs-parent="#tramiteFullAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label for="title_full">Título</label>
                                                <input type="text" name="title_full" id="title_full" class="form-control" value="{{ old('title_full') }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="title_short">Subtítulo</label>
                                                <input type="text" name="title_short" id="title_short" class="form-control" value="{{ old('title_short') }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Descripción</label>
                                                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                            </div>

                                            <div class="form-group form-check form-switch">
                                                <input type="hidden" name="is_published" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published') == 1)>
                                                <label class="form-check-label" for="is_published">Publicado</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="tramiteIconHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tramiteIcon" aria-expanded="false" aria-controls="tramiteIcon">
                                            Icono / Logo
                                        </button>
                                    </h2>
                                    <div id="tramiteIcon" class="accordion-collapse collapse" aria-labelledby="tramiteIconHeading" data-bs-parent="#tramiteFullAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label>Icono — seleccione uno de los disponibles o suba una imagen</label>
                                                <div class="mt-2">
                                                    <label class="d-block mb-1">Selector de iconos rápidos</label>
                                                    <div class="icon-picker d-flex flex-wrap" id="iconPicker">
                                                        @php
                                                            $icons = [
                                                                'fas fa-file-alt', 'fas fa-id-card', 'fas fa-user', 'fas fa-home',
                                                                'fas fa-university', 'fas fa-file-contract', 'fas fa-book', 'fas fa-phone',
                                                                'fas fa-envelope', 'fas fa-cogs', 'fas fa-clipboard-list', 'fas fa-check-circle'
                                                            ];
                                                        @endphp
                                                        @foreach($icons as $ic)
                                                            <button type="button" class="icon-item btn btn-light m-1" data-class="{{ $ic }}" title="{{ $ic }}">
                                                                <i class="{{ $ic }}"></i>
                                                            </button>
                                                        @endforeach
                                                        <button type="button" id="clearIcon" class="btn btn-outline-secondary m-1">Limpiar</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="logo_class" id="logo_class" value="{{ old('logo_class') }}">
                                                <small class="text-muted d-block mt-2">Si subes una imagen, ésta prevalecerá sobre el icono seleccionado.</small>
                                            </div>

                                            <div class="form-group">
                                                <label for="logo_image">Logo (imagen) — opcional</label>
                                                <input type="file" name="logo_image" id="logo_image" class="form-control-file" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="tramiteContentHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tramiteContent" aria-expanded="false" aria-controls="tramiteContent">
                                            Contenido
                                        </button>
                                    </h2>
                                    <div id="tramiteContent" class="accordion-collapse collapse" aria-labelledby="tramiteContentHeading" data-bs-parent="#tramiteFullAccordion">
                                        <div class="accordion-body p-0">
                                            <div class="form-group" id="contentGroup">
                                                <textarea name="content" id="content" class="form-control summernote">{{ old('content') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="tramiteLinkHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tramiteLink" aria-expanded="false" aria-controls="tramiteLink">
                                            Redirección / Link
                                        </button>
                                    </h2>
                                    <div id="tramiteLink" class="accordion-collapse collapse" aria-labelledby="tramiteLinkHeading" data-bs-parent="#tramiteFullAccordion">
                                        <div class="accordion-body p-0">
                                            <div class="form-group" id="redirectGroup">
                                                <input type="url" name="redirect_url" id="redirect_url" class="form-control" value="{{ old('redirect_url') }}" placeholder="https://example.com/documento.pdf">
                                                <small class="form-text text-muted">Si la URL termina en .pdf se mostrará embebida; si es una URL normal se intentará cargar dentro de la página.</small>
                                                @error('redirect_url') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('tramites.index') }}" class="btn btn-light">Cancelar</a>
                                <button class="btn btn-gradient-primary" type="submit">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/summernote/summernote.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('assets/website/plugins/summernote/summernote.min.js') }}"></script>
    <script>
        $(function () {
            $('#content').summernote();

            // Icon picker logic
            var $iconInput = $('#logo_class');
            var $iconPicker = $('#iconPicker');

            function selectIconClass(cls) {
                $iconInput.val(cls).trigger('input');
                $iconPicker.find('.icon-item').removeClass('active border-primary');
                $iconPicker.find('.icon-item[data-class="' + cls + '"]').addClass('active border-primary');
            }

            // initialize selection from input value
            var initial = $iconInput.val();
            if (initial) {
                selectIconClass(initial);
            }

            $iconPicker.on('click', '.icon-item', function () {
                var cls = $(this).data('class');
                selectIconClass(cls);
            });

            $('#clearIcon').on('click', function () {
                $iconInput.val('');
                $iconPicker.find('.icon-item').removeClass('active border-primary');
            });

            // Toggle between content and link mode and sync with accordion
            function toggleMode() {
                var mode = $('#mode').val();

                // Ensure accordion panels match selected mode (add/remove 'show' class)
                if (mode === 'link') {
                    $('#tramiteLink').addClass('show');
                    $('#tramiteContent').removeClass('show');

                    $('[data-bs-target="#tramiteLink"]').removeClass('collapsed').attr('aria-expanded', 'true');
                    $('[data-bs-target="#tramiteContent"]').addClass('collapsed').attr('aria-expanded', 'false');

                    $('#redirectGroup').show();
                    $('#contentGroup').hide();
                } else {
                    $('#tramiteContent').addClass('show');
                    $('#tramiteLink').removeClass('show');

                    $('[data-bs-target="#tramiteContent"]').removeClass('collapsed').attr('aria-expanded', 'true');
                    $('[data-bs-target="#tramiteLink"]').addClass('collapsed').attr('aria-expanded', 'false');

                    $('#redirectGroup').hide();
                    $('#contentGroup').show();
                }
            }

            // Use accordion collapse events to decide which mode to save
            $('#tramiteLink').on('shown.bs.collapse', function () {
                $('#mode').val('link');
                toggleMode();
            });
            $('#tramiteContent').on('shown.bs.collapse', function () {
                $('#mode').val('content');
                toggleMode();
            });

            // initialize on page load based on hidden field or current open panel
            if (!$('#mode').val()) {
                // fallback: detect expanded panel
                $('#mode').val($('#tramiteLink').hasClass('show') ? 'link' : 'content');
            }
            toggleMode();
        });
    </script>
@endsection
