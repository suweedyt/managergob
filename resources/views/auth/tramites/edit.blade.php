@extends('layouts.auth')

@section('title', 'Editar Trámite')

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Editar Trámite </h3>
        </div>

        <div class="row">
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('tramites.update', $tramite->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="title_full">Título completo</label>
                                <input type="text" name="title_full" id="title_full" class="form-control" value="{{ old('title_full', $tramite->title_full) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="title_short">Título corto</label>
                                <input type="text" name="title_short" id="title_short" class="form-control" value="{{ old('title_short', $tramite->title_short) }}" required>
                            </div>

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
                                <input type="hidden" name="logo_class" id="logo_class" value="{{ old('logo_class', $tramite->logo_class) }}">
                                <small class="text-muted d-block mt-2">Si subes una imagen, ésta prevalecerá sobre el icono seleccionado.</small>
                            </div>

                            <div class="form-group">
                                <label for="logo_image">Logo (imagen) — opcional</label>
                                <input type="file" name="logo_image" id="logo_image" class="form-control-file" accept="image/*">
                                @if($tramite->logo_image)
                                    <div class="mt-2"><img src="{{ asset($tramite->logo_image) }}" alt="logo" style="max-width:120px;"></div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="description">Descripción corta</label>
                                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $tramite->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="content">Contenido</label>
                                <textarea name="content" id="content" class="form-control summernote">{{ old('content', $tramite->content) }}</textarea>
                            </div>

                            <div class="form-group form-check form-switch">
                                <input type="hidden" name="is_published" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $tramite->is_published) == 1)>
                                <label class="form-check-label" for="is_published">Publicado</label>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('tramites.index') }}" class="btn btn-light">Cancelar</a>
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

            // Icon picker logic (same as create)
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
        });
    </script>
@endsection
