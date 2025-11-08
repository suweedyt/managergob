@extends('layouts.auth')

@section('title', 'Editar Noticia')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/summernote/summernote.min.css') }}">
    <style>
        .slider-preview-wrapper {
            display: none;
            margin-top: 1rem;
        }

        .slider-preview {
            width: 100%;
            max-width: 640px;
            height: 280px;
            background-color: #1f1f1f;
            background-size: cover;
            background-position: 50% 50%;
            border-radius: 8px;
            border: 1px solid #d4d4d4;
            position: relative;
            cursor: grab;
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }

        .slider-preview.is-dragging {
            cursor: grabbing;
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.28);
        }

        .slider-preview-overlay {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 12px;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .slider-preview.no-helper .slider-preview-overlay {
            opacity: 0;
        }
    </style>
@endsection

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Noticias </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Noticias</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Noticia</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="header-subsection">
                            <h4 class="card-title">Editar Noticia</h4>
                            <div>
                                <a href="{{ route('posts.index') }}" id="btn-back-to-list" class="btn btn-sm btn-outline-secondary">← Volver a la lista</a>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li> {{ $error }} </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('posts.update', $post->id) }}" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="title">Titulo</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="Titulo" value="{{ old('title', $post->title) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="category">Categoria</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="" disabled>Seleccione una categoria</option>
                                    @foreach ($categories as $category)
                                        <option @selected(old('category', $post->category_id) == $category->id) value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_published">Publicar</label>
                                <select class="form-control" id="is_published" name="is_published" required>
                                    <option value="" disabled>Seleccione una opcion</option>
                                    <option @selected(old('is_published', $post->is_published) == 1) value="1">Si</option>
                                    <option @selected(old('is_published', $post->is_published) == 0) value="0">No</option>
                                </select>
                            </div>

                            <div class="form-group form-check">
                                <input type="hidden" name="is_slider" value="0">
                                <input type="checkbox" class="form-check-input" id="is_slider" name="is_slider" value="1" @checked(old('is_slider', $post->is_slider))>
                                <label class="form-check-label" for="is_slider">Mostrar en Banner</label>
                            </div>

                            <div class="form-group form-check">
                                <input type="hidden" name="is_news_slider" value="0">
                                <input type="checkbox" class="form-check-input" id="is_news_slider" name="is_news_slider" value="1" @checked(old('is_news_slider', $post->is_news_slider))>
                                <label class="form-check-label" for="is_news_slider">Mostrar slider home</label>
                            </div>

                            <div class="form-group" id="slider-file-group" style="display: none;">
                                <label>Cargar Imagen Principal (dejar vacío para no cambiar)</label>
                                <div class="input-group col-xs-12">
                                    <input type="file" name="slider_file" id="slider_file" class="form-control file-upload-info" placeholder="Cargar Imagen principal" accept="image/*">
                                </div>
                                <input type="hidden" name="slider_position_x" id="slider_position_x" value="{{ old('slider_position_x', $post->slider_position_x ?? 50) }}">
                                <input type="hidden" name="slider_position_y" id="slider_position_y" value="{{ old('slider_position_y', $post->slider_position_y ?? 50) }}">
                                <div class="slider-preview-wrapper" id="slider_preview_wrapper">
                                    <div class="slider-preview" id="slider_preview" data-initial-image="{{ $post->sliderGallery ? asset($post->sliderGallery->image) : ($post->gallery ? asset($post->gallery->image) : '') }}" data-initial-x="{{ $post->slider_position_x ?? 50 }}" data-initial-y="{{ $post->slider_position_y ?? 50 }}">
                                        <div class="slider-preview-overlay">Arrastra para ajustar la posición</div>
                                    </div>
                                </div>
                                @if($post->sliderGallery)
                                    <div class="mt-2">
                                        <img src="{{ asset($post->sliderGallery->image) }}" alt="slider" style="max-width:200px;">
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="description">Descripcion</label>
                                <textarea id="summernote" class="form-control" name="description" minlength="10" required>{{ old('description', $post->description) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-gradient-primary me-2">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/website/plugins/summernote/summernote.min.js') }}"></script>
    <script>
        $(function () {
            $('#summernote').summernote();

            var $sliderCheckbox = $('#is_slider');
            var $sliderGroup = $('#slider-file-group');
            var $sliderFile = $('#slider_file');
            var $previewWrapper = $('#slider_preview_wrapper');
            var $preview = $('#slider_preview');
            var $posX = $('#slider_position_x');
            var $posY = $('#slider_position_y');
            var $helper = $preview.find('.slider-preview-overlay');
            var dragging = false;
            var activePointerId = null;

            function clamp(value) {
                var num = parseFloat(value);
                if (isNaN(num)) {
                    num = 50;
                }

                return Math.max(0, Math.min(100, num));
            }

            function updatePosition(x, y) {
                var clampedX = Math.round(clamp(x));
                var clampedY = Math.round(clamp(y));

                $preview.css('background-position', clampedX + '% ' + clampedY + '%');
                $posX.val(clampedX);
                $posY.val(clampedY);
            }

            function showPreview() {
                if ($sliderCheckbox.is(':checked') && $preview.hasClass('has-image')) {
                    $previewWrapper.stop(true, true).fadeIn(150);
                } else {
                    $previewWrapper.hide();
                }
            }

            function setPreview(imageUrl, x, y) {
                if (!imageUrl) {
                    clearPreview();
                    return;
                }

                $preview
                    .css('background-image', 'url("' + imageUrl + '")')
                    .addClass('has-image')
                    .removeClass('no-helper');

                $helper.text('Arrastra para ajustar la posición');
                updatePosition(typeof x === 'undefined' ? $posX.val() : x, typeof y === 'undefined' ? $posY.val() : y);
                showPreview();
            }

            function clearPreview() {
                $preview
                    .css('background-image', '')
                    .removeClass('has-image is-dragging no-helper');

                $helper.text('Arrastra para ajustar la posición');
                $previewWrapper.hide();
            }

            function toggleSlider() {
                if ($sliderCheckbox.is(':checked')) {
                    $sliderGroup.slideDown(150);
                    showPreview();
                } else {
                    $sliderGroup.slideUp(150);
                    $previewWrapper.hide();
                }
            }

            $sliderCheckbox.on('change', toggleSlider);

            $sliderFile.on('change', function (event) {
                var file = event.target.files[0];

                if (!file) {
                    clearPreview();
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (e) {
                    setPreview(e.target.result, 50, 50);
                };
                reader.readAsDataURL(file);
            });

            function pointerToPosition(event) {
                if (!$preview.hasClass('has-image')) {
                    return;
                }

                var original = event.originalEvent || event;
                var rect = $preview[0].getBoundingClientRect();
                var xPercent = ((original.clientX - rect.left) / rect.width) * 100;
                var yPercent = ((original.clientY - rect.top) / rect.height) * 100;

                updatePosition(xPercent, yPercent);
            }

            $preview.on('pointerdown', function (event) {
                if (!$preview.hasClass('has-image')) {
                    return;
                }

                dragging = true;
                activePointerId = event.originalEvent.pointerId;
                $preview.addClass('is-dragging');
                pointerToPosition(event);
                event.preventDefault();
            });

            $(document).on('pointermove', function (event) {
                if (!dragging || event.originalEvent.pointerId !== activePointerId) {
                    return;
                }

                pointerToPosition(event);
            });

            $(document).on('pointerup pointercancel', function (event) {
                if (!dragging || event.originalEvent.pointerId !== activePointerId) {
                    return;
                }

                dragging = false;
                activePointerId = null;
                $preview.removeClass('is-dragging').addClass('no-helper');
            });

            var initialImage = $preview.data('initial-image');
            if (initialImage) {
                var initialX = clamp($preview.data('initial-x'));
                var initialY = clamp($preview.data('initial-y'));
                setPreview(initialImage, initialX, initialY);
            }

            toggleSlider();
        });
    </script>
@endsection
