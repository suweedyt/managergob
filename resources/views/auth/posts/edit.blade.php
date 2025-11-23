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

                            <div class="accordion" id="postEditAccordion">
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="postEditGeneralHeading">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#postEditGeneral" aria-expanded="true" aria-controls="postEditGeneral">
                                            Configuración general
                                        </button>
                                    </h2>
                                    <div id="postEditGeneral" class="accordion-collapse collapse show" aria-labelledby="postEditGeneralHeading" data-bs-parent="#postEditAccordion">
                                        <div class="accordion-body">
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

                                            <div class="form-group form-check form-switch">
                                                <input type="hidden" name="is_published" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $post->is_published) == 1)>
                                                <label class="form-check-label" for="is_published">Publicar</label>
                                            </div>

                                            <div class="form-group">
                                                <label for="file">Imagen Principal (dejar vacío para no cambiar)</label>
                                                <div class="input-group col-xs-12">
                                                    <input type="file" name="file" id="main_file" class="form-control file-upload-info" placeholder="Cargar Imagen Principal" accept="image/*">
                                                </div>
                                                @if($post->gallery)
                                                    <div class="mt-2">
                                                        <img src="{{ asset($post->gallery->image) }}" alt="principal" style="max-width:200px;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="postEditBannerHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#postEditBanner" aria-expanded="false" aria-controls="postEditBanner">
                                            Configuración de banner
                                        </button>
                                    </h2>
                                    <div id="postEditBanner" class="accordion-collapse collapse" aria-labelledby="postEditBannerHeading" data-bs-parent="#postEditAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group form-check form-switch mb-3">
                                                <input type="hidden" name="is_slider" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_slider" name="is_slider" value="1" @checked(old('is_slider', $post->is_slider))>
                                                <label class="form-check-label" for="is_slider">Mostrar en Banner</label>
                                            </div>

                                            <div class="form-group mb-3" id="banner_short_description_group">
                                                <label for="banner_short_description">Descripción corta para banner</label>
                                                <textarea name="banner_short_description" id="banner_short_description" class="form-control" maxlength="180" rows="3" placeholder="Ingresa una descripción corta para mostrar en el banner">{{ old('banner_short_description', $post->banner_short_description) }}</textarea>
                                                <small class="form-text text-muted">Se mostrará cuando la noticia sea destacada como banner.</small>
                                            </div>

                                            <div class="form-group" id="slider-file-group" style="display: none;">
                                                <label>Opciones de Banner</label>

                                                <div class="form-group form-check form-switch mb-3">
                                                    <input type="hidden" name="banner_use_different" value="0">
                                                    <input type="checkbox" role="switch" class="form-check-input" id="banner_use_different" name="banner_use_different" value="1" @checked(old('banner_use_different', isset($post->sliderGallery) && $post->sliderGallery && (($post->gallery->id ?? null) !== ($post->sliderGallery->id ?? null))))>
                                                    <label class="form-check-label" for="banner_use_different">Usar imagen distinta para el banner</label>
                                                </div>

                                                <div id="banner-file-input" style="display: none;">
                                                    <label>Cargar Imagen para banner (dejar vacío para no cambiar)</label>
                                                    <div class="input-group col-xs-12">
                                                        <input type="file" name="slider_file" id="slider_file" class="form-control file-upload-info" placeholder="Cargar Imagen para slider" accept="image/*">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="slider_position_x" id="slider_position_x" value="{{ old('slider_position_x', $post->slider_position_x ?? 50) }}">
                                                <input type="hidden" name="slider_position_y" id="slider_position_y" value="{{ old('slider_position_y', $post->slider_position_y ?? 50) }}">
                                                <div class="slider-preview-wrapper" id="slider_preview_wrapper">
                                                    <div class="slider-preview" id="slider_preview" data-initial-image="{{ $post->sliderGallery ? asset($post->sliderGallery->image) : ($post->gallery ? asset($post->gallery->image) : '') }}" data-initial-x="{{ $post->slider_position_x ?? 50 }}" data-initial-y="{{ $post->slider_position_y ?? 50 }}">
                                                        <div class="slider-preview-overlay">Arrastra para ajustar la posición</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="postEditNewsSliderHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#postEditNewsSlider" aria-expanded="false" aria-controls="postEditNewsSlider">
                                            Slider del home
                                        </button>
                                    </h2>
                                    <div id="postEditNewsSlider" class="accordion-collapse collapse" aria-labelledby="postEditNewsSliderHeading" data-bs-parent="#postEditAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group form-check form-switch">
                                                <input type="hidden" name="is_news_slider" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_news_slider" name="is_news_slider" value="1" @checked(old('is_news_slider', $post->is_news_slider))>
                                                <label class="form-check-label" for="is_news_slider">Mostrar slider home</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="postEditDescriptionHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#postEditDescription" aria-expanded="false" aria-controls="postEditDescription">
                                            Descripción de la noticia
                                        </button>
                                    </h2>
                                    <div id="postEditDescription" class="accordion-collapse collapse" aria-labelledby="postEditDescriptionHeading" data-bs-parent="#postEditAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label for="description">Descripcion</label>
                                                <textarea id="summernote" class="form-control" name="description" minlength="10" required>{{ old('description', $post->description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gradient-primary me-2 mt-3">Actualizar</button>
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
            var $bannerDifferent = $('#banner_use_different');
            var $bannerFileInputWrapper = $('#banner-file-input');
            var $sliderFile = $('#slider_file');
            var $mainFile = $('#main_file');
            var $previewWrapper = $('#slider_preview_wrapper');
            var $preview = $('#slider_preview');
            var $posX = $('#slider_position_x');
            var $posY = $('#slider_position_y');
            var $helper = $preview.find('.slider-preview-overlay');
            var $bannerShortDescriptionGroup = $('#banner_short_description').closest('.form-group');
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
                if (!$sliderCheckbox.is(':checked')) {
                    $previewWrapper.hide();
                    return;
                }

                if ($preview.hasClass('has-image')) {
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
                    $bannerShortDescriptionGroup.slideDown(150);
                    showPreview();
                } else {
                    $sliderGroup.slideUp(150);
                    $bannerShortDescriptionGroup.slideUp(150);
                    $previewWrapper.hide();
                }
            }

            $sliderCheckbox.on('change', toggleSlider);

            // bannerDifferent toggle
            $bannerDifferent.on('change', function () {
                if ($(this).is(':checked')) {
                    $bannerFileInputWrapper.slideDown(120);
                    if ($sliderFile[0] && $sliderFile[0].files && $sliderFile[0].files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) { setPreview(e.target.result, 50, 50); };
                        reader.readAsDataURL($sliderFile[0].files[0]);
                    }
                } else {
                    $bannerFileInputWrapper.slideUp(120);
                    // fallback to main image if present
                    if ($mainFile[0] && $mainFile[0].files && $mainFile[0].files[0]) {
                        var reader2 = new FileReader();
                        reader2.onload = function (e) { setPreview(e.target.result, 50, 50); };
                        reader2.readAsDataURL($mainFile[0].files[0]);
                    } else {
                        // if no main file selected, try to use existing gallery image (initialImage)
                        var initialImage = $preview.data('initial-image');
                        if (initialImage) { setPreview(initialImage, clamp($preview.data('initial-x')), clamp($preview.data('initial-y'))); }
                    }
                }
            });

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

            // when main image changes, update banner preview if banner uses main image
            $mainFile.on('change', function (event) {
                var file = event.target.files[0];
                if (!file) { return; }
                var reader = new FileReader();
                reader.onload = function (e) {
                    if ($sliderCheckbox.is(':checked') && !$bannerDifferent.is(':checked')) {
                        setPreview(e.target.result, 50, 50);
                    }
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

            // ensure banner input visibility reflects saved state on load
            if ($bannerDifferent.is(':checked')) {
                $bannerFileInputWrapper.show();
            }
        });
    </script>
@endsection
