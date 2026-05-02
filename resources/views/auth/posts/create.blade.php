@extends('layouts.auth')

@section('title', 'Crear Noticia')

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
                    <li class="breadcrumb-item active" aria-current="page">Crear Noticia</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="header-subsection">
                            <h4 class="card-title">Crear Noticia</h4>
                            <div>
                                <a href="{{ route('posts.index') }}" id="btn-back-to-list" type="submit" class="btn btn-sm btn-outline-secondary">← Volver a la lista</a>
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

                        <form method="POST" action="{{ route('posts.store') }}" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            <div class="accordion" id="postCreateAccordion">
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="postGeneralHeading">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#postGeneral" aria-expanded="true" aria-controls="postGeneral">
                                            Configuración general
                                        </button>
                                    </h2>
                                    <div id="postGeneral" class="accordion-collapse collapse show" aria-labelledby="postGeneralHeading" data-bs-parent="#postCreateAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label for="title">Titulo</label>
                                                <input type="text" name="title" class="form-control" id="title" placeholder="Titulo" value="{{ old('title') }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="category">Categoria</label>
                                                <select class="form-control" id="category" name="category" required>
                                                    <option value="" disabled selected>Seleccione una categoria</option>
                                                    @if (count($categories) > 0)
                                                        @foreach ($categories as $category)
                                                            <option @selected(old('category') == $category->id) value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group form-check form-switch">
                                                <input type="hidden" name="is_published" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published') == 1)>
                                                <label class="form-check-label" for="is_published">Publicar</label>
                                            </div>

                                            <div class="form-group">
                                                <label for="file">Imagen Principal</label>
                                                <div class="input-group col-xs-12">
                                                    <input type="file" name="file" id="main_file" class="form-control file-upload-info" placeholder="Cargar Imagen Principal" accept="image/*" required>
                                                </div>
                                                <div id="main_image_preview" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="postBannerHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#postBanner" aria-expanded="false" aria-controls="postBanner">
                                            Configuración de banner
                                        </button>
                                    </h2>
                                    <div id="postBanner" class="accordion-collapse collapse" aria-labelledby="postBannerHeading" data-bs-parent="#postCreateAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group form-check form-switch mb-3">
                                                <input type="hidden" name="is_slider" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_slider" name="is_slider" value="1" @checked(old('is_slider'))>
                                                <label class="form-check-label" for="is_slider">Mostrar en Banner</label>
                                            </div>

                                            <div class="form-group mb-3" id="banner_short_description_group">
                                                <label for="banner_short_description">Descripción corta para banner</label>
                                                <textarea name="banner_short_description" id="banner_short_description" class="form-control" maxlength="180" rows="3" placeholder="Ingresa una descripción corta para mostrar en el banner">{{ old('banner_short_description') }}</textarea>
                                                <small class="form-text text-muted">Se mostrará cuando la noticia sea destacada como banner.</small>
                                            </div>

                                            <div class="form-group" id="slider-file-group" style="display: none;">
                                                <label>Opciones de Banner</label>

                                                <div class="form-group form-check">
                                                    <input type="hidden" name="banner_use_different" value="0">
                                                    <input type="checkbox" class="form-check-input" id="banner_use_different" name="banner_use_different" value="1" @checked(old('banner_use_different'))>
                                                    <label class="form-check-label" for="banner_use_different">Usar imagen distinta para el banner</label>
                                                </div>

                                                <div id="banner-file-input" style="display: none;">
                                                    <label>Cargar Imagen para banner</label>
                                                    <div class="input-group col-xs-12">
                                                        <input type="file" name="slider_file" id="slider_file" class="form-control file-upload-info" placeholder="Cargar Imagen para slider" accept="image/*">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="slider_position_x" id="slider_position_x" value="{{ old('slider_position_x', 50) }}">
                                                <input type="hidden" name="slider_position_y" id="slider_position_y" value="{{ old('slider_position_y', 50) }}">
                                                <div class="slider-preview-wrapper" id="slider_preview_wrapper">
                                                    <div class="slider-preview" id="slider_preview" data-initial-image="" data-initial-x="{{ old('slider_position_x', 50) }}" data-initial-y="{{ old('slider_position_y', 50) }}">
                                                        <div class="slider-preview-overlay">Arrastra para ajustar la posición</div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-4">
                                                        <div class="form-group form-check">
                                                            <input type="hidden" name="banner_show_caption" value="0">
                                                            <input type="checkbox" class="form-check-input" id="banner_show_caption" name="banner_show_caption" value="1" @checked(old('banner_show_caption', 1))>
                                                            <label class="form-check-label" for="banner_show_caption">Mostrar título y descripción en el front</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="banner_button_text">Texto botón</label>
                                                            <input type="text" name="banner_button_text" id="banner_button_text" class="form-control" value="{{ old('banner_button_text') }}" placeholder="Leer más">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="banner_button_bg_color">Color botón</label>
                                                            <input type="color" name="banner_button_bg_color" id="banner_button_bg_color" class="form-control-color" value="{{ old('banner_button_bg_color', '#0d6efd') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="postNewsSliderHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#postNewsSlider" aria-expanded="false" aria-controls="postNewsSlider">
                                            Slider del home
                                        </button>
                                    </h2>
                                    <div id="postNewsSlider" class="accordion-collapse collapse" aria-labelledby="postNewsSliderHeading" data-bs-parent="#postCreateAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group form-check form-switch">
                                                <input type="hidden" name="is_news_slider" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_news_slider" name="is_news_slider" value="1" @checked(old('is_news_slider'))>
                                                <label class="form-check-label" for="is_news_slider">Mostrar slider home</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="postDescriptionHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#postDescription" aria-expanded="false" aria-controls="postDescription">
                                            Descripción de la noticia
                                        </button>
                                    </h2>
                                    <div id="postDescription" class="accordion-collapse collapse" aria-labelledby="postDescriptionHeading" data-bs-parent="#postCreateAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label for="description">Descripcion</label>
                                                <textarea id="summernote" class="form-control" name="description" minlength="10" required>{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gradient-primary me-2 mt-3">Crear</button>
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
            var $bannerShortDescriptionGroup = $('#banner_short_description_group');
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
                    if ($mainFile[0] && $mainFile[0].files && $mainFile[0].files[0]) {
                        var reader2 = new FileReader();
                        reader2.onload = function (e) { setPreview(e.target.result, 50, 50); };
                        reader2.readAsDataURL($mainFile[0].files[0]);
                    } else {
                        clearPreview();
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

            $mainFile.on('change', function (event) {
                var file = event.target.files[0];
                if (!file) {
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (e) {
                    if ($sliderCheckbox.is(':checked') && !$bannerDifferent.is(':checked')) {
                        setPreview(e.target.result, 50, 50);
                    }
                };
                reader.readAsDataURL(file);
            });

            // main image thumbnail preview (create)
            function setMainPreview(url) {
                var $target = $('#main_image_preview');
                if (!url) { $target.html(''); return; }
                $target.html('<img src="' + url + '" alt="preview" style="max-width:200px;" />');
            }

            $mainFile.on('change', function (event) {
                var file = event.target.files[0];
                if (!file) { setMainPreview(''); return; }
                var reader = new FileReader();
                reader.onload = function (e) { setMainPreview(e.target.result); };
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

            toggleSlider();

            if ($bannerDifferent.is(':checked')) {
                $bannerFileInputWrapper.show();
            }

            if (!$sliderCheckbox.is(':checked')) {
                $bannerShortDescriptionGroup.hide();
            }
        });
    </script>
@endsection