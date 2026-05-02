@extends('layouts.auth')

@section('title', 'Editar Sección')

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Secciones </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Secciones</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Sección</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="header-subsection">
                            <h4 class="card-title">Editar Sección</h4>
                            <div>
                                <a href="{{ route('sections.index') }}" id="btn-back-to-list" class="btn btn-sm btn-outline-secondary">← Volver a la lista</a>
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

                        <form action="{{ route('sections.update', $section) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="mode" id="mode" value="{{ old('mode', $section->mode) }}">

                            <div class="accordion" id="sectionAccordion">
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="sectionGeneralHeading">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#sectionGeneral" aria-expanded="true" aria-controls="sectionGeneral">
                                            General
                                        </button>
                                    </h2>
                                    <div id="sectionGeneral" class="accordion-collapse collapse show" aria-labelledby="sectionGeneralHeading" data-bs-parent="#sectionAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label for="title_full">Título</label>
                                                <input type="text" name="title_full" id="title_full" class="form-control" value="{{ old('title_full', $section->title_full) }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="title_short">Subtítulo</label>
                                                <input type="text" name="title_short" id="title_short" class="form-control" value="{{ old('title_short', $section->title_short) }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Descripción</label>
                                                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $section->description) }}</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="order">Orden</label>
                                                <input type="number" min="0" name="order" id="order" class="form-control" value="{{ old('order', $section->order) }}">
                                            </div>

                                            <div class="form-group form-check form-switch">
                                                <input type="hidden" name="is_published" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $section->is_published))>
                                                <label class="form-check-label" for="is_published">Publicado</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="sectionIconHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sectionIcon" aria-expanded="false" aria-controls="sectionIcon">
                                            Icono / Logo
                                        </button>
                                    </h2>
                                    <div id="sectionIcon" class="accordion-collapse collapse" aria-labelledby="sectionIconHeading" data-bs-parent="#sectionAccordion">
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
                                                <input type="hidden" name="logo_class" id="logo_class" value="{{ old('logo_class', $section->logo_class) }}">
                                                <small class="text-muted d-block mt-2">Si subes una imagen, ésta prevalecerá sobre el icono seleccionado.</small>
                                            </div>

                                            <div class="form-group">
                                                <label for="logo_image">Logo (imagen) — opcional</label>
                                                <input type="file" name="logo_image" id="logo_image" class="form-control" accept="image/*">
                                                @if($section->logo_image)
                                                    <div class="mt-2"><img src="{{ asset($section->logo_image) }}" alt="logo" style="max-width: 120px;"></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="sectionContentHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sectionContent" aria-expanded="false" aria-controls="sectionContent">
                                            Contenido
                                        </button>
                                    </h2>
                                    <div id="sectionContent" class="accordion-collapse collapse" aria-labelledby="sectionContentHeading" data-bs-parent="#sectionAccordion">
                                        <div class="accordion-body p-0">
                                            <div class="form-group" id="contentGroup">
                                                <textarea name="content" id="content" class="form-control summernote">{{ old('content', $section->content) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="sectionLinkHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sectionLink" aria-expanded="false" aria-controls="sectionLink">
                                            Redirección / Link
                                        </button>
                                    </h2>
                                    <div id="sectionLink" class="accordion-collapse collapse" aria-labelledby="sectionLinkHeading" data-bs-parent="#sectionAccordion">
                                        <div class="accordion-body p-0">
                                            <div class="form-group" id="redirectGroup">
                                                <input type="url" name="redirect_url" id="redirect_url" class="form-control" value="{{ old('redirect_url', $section->redirect_url) }}" placeholder="https://example.com/documento.pdf">
                                                <small class="form-text text-muted">Si la URL termina en .pdf se mostrará embebida; si es una URL normal se intentará cargar dentro de la página.</small>
                                                @error('redirect_url') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('sections.index') }}" class="btn btn-light">Cancelar</a>
                                <button class="btn btn-gradient-primary" type="submit">Actualizar</button>
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

            var $iconInput = $('#logo_class');
            var $iconPicker = $('#iconPicker');

            function selectIconClass(cls) {
                $iconInput.val(cls).trigger('input');
                $iconPicker.find('.icon-item').removeClass('active border-primary');
                $iconPicker.find('.icon-item[data-class="' + cls + '"]').addClass('active border-primary');
            }

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

            function toggleMode() {
                var mode = $('#mode').val();
                if (mode === 'link') {
                    $('#sectionLink').addClass('show');
                    $('#sectionContent').removeClass('show');
                    $('[data-bs-target="#sectionLink"]').removeClass('collapsed').attr('aria-expanded', 'true');
                    $('[data-bs-target="#sectionContent"]').addClass('collapsed').attr('aria-expanded', 'false');
                    $('#redirectGroup').show();
                    $('#contentGroup').hide();
                } else {
                    $('#sectionContent').addClass('show');
                    $('#sectionLink').removeClass('show');
                    $('[data-bs-target="#sectionContent"]').removeClass('collapsed').attr('aria-expanded', 'true');
                    $('[data-bs-target="#sectionLink"]').addClass('collapsed').attr('aria-expanded', 'false');
                    $('#redirectGroup').hide();
                    $('#contentGroup').show();
                }
            }

            $('#sectionLink').on('shown.bs.collapse', function () {
                $('#mode').val('link');
                toggleMode();
            });
            $('#sectionContent').on('shown.bs.collapse', function () {
                $('#mode').val('content');
                toggleMode();
            });

            if (!$('#mode').val()) {
                $('#mode').val($('#sectionLink').hasClass('show') ? 'link' : 'content');
            }
            toggleMode();
        });
    </script>
@endsection
