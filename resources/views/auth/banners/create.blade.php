@extends('layouts.auth')

@section('title', 'Crear Banner')

@section('styles')
    <style>
        .slider-preview-wrapper { display: none; margin-top: 1rem; }
        .slider-preview { width: 100%; max-width: 640px; height: 280px; background-color: #1f1f1f; background-size: cover; background-position: 50% 50%; border-radius: 8px; border: 1px solid #d4d4d4; position: relative; cursor: grab; overflow: hidden; transition: box-shadow 0.2s ease; }
        .slider-preview.is-dragging { cursor: grabbing; box-shadow: 0 12px 26px rgba(0, 0, 0, 0.28); }
        .slider-preview-overlay { position: absolute; bottom: 12px; right: 12px; background: rgba(0, 0, 0, 0.65); color: #fff; padding: 6px 10px; border-radius: 4px; font-size: 12px; pointer-events: none; transition: opacity 0.3s ease; }
        .slider-preview.no-helper .slider-preview-overlay { opacity: 0; }
    </style>
@endsection

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Banners </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('banners.index') }}">Banners</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crear Banner</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="header-subsection">
                            <h4 class="card-title">Crear Banner</h4>
                            <div>
                                <a href="{{ route('banners.index') }}" id="btn-back-to-list" class="btn btn-sm btn-outline-secondary">← Volver a la lista</a>
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

                        <form method="POST" action="{{ route('banners.store') }}" class="forms-sample" enctype="multipart/form-data">
                            @csrf

                            <div class="accordion" id="bannerAccordion">
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="bannerGeneralHeading">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#bannerGeneral" aria-expanded="true" aria-controls="bannerGeneral">
                                            General
                                        </button>
                                    </h2>
                                    <div id="bannerGeneral" class="accordion-collapse collapse show" aria-labelledby="bannerGeneralHeading" data-bs-parent="#bannerAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="title">Título</label>
                                                        <input type="text" name="title" class="form-control" id="title" placeholder="Título" value="{{ old('title') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="button_text">Texto del botón</label>
                                                        <input type="text" name="button_text" id="button_text" class="form-control" placeholder="Ver más" value="{{ old('button_text') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="button_bg_color">Color del botón</label>
                                                        <input type="color" name="button_bg_color" id="button_bg_color" class="form-control form-control-color" value="{{ old('button_bg_color', '#0069d9') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="button_url">URL del botón</label>
                                                        <input type="url" name="button_url" id="button_url" class="form-control" placeholder="https://" value="{{ old('button_url') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-check form-switch">
                                                        <input type="hidden" name="is_published" value="0">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', 1))>
                                                        <label class="form-check-label" for="is_published">Publicar</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="bannerDescriptionsHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bannerDescriptions" aria-expanded="false" aria-controls="bannerDescriptions">
                                            Descripciones
                                        </button>
                                    </h2>
                                    <div id="bannerDescriptions" class="accordion-collapse collapse" aria-labelledby="bannerDescriptionsHeading" data-bs-parent="#bannerAccordion">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label for="short_description">Descripción corta</label>
                                                <textarea name="short_description" id="short_description" class="form-control" maxlength="180" rows="2" placeholder="Ingresa una descripción corta">{{ old('short_description') }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="long_description">Descripción larga</label>
                                                <textarea id="long_description" class="form-control" name="long_description" rows="6">{{ old('long_description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="bannerMediaHeading">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bannerMedia" aria-expanded="false" aria-controls="bannerMedia">
                                            Media
                                        </button>
                                    </h2>
                                    <div id="bannerMedia" class="accordion-collapse collapse" aria-labelledby="bannerMediaHeading" data-bs-parent="#bannerAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="media_type">Tipo de media</label>
                                                        <select class="form-control" id="media_type" name="media_type" required>
                                                            <option value="image" @selected(old('media_type')==='image')>Imagen</option>
                                                            <option value="video" @selected(old('media_type')==='video')>Video</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Fuente de media</label>
                                                        <div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="media_source" id="source_upload" value="upload" checked>
                                                                <label class="form-check-label" for="source_upload">Subir archivo</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="media_source" id="source_url" value="url">
                                                                <label class="form-check-label" for="source_url">URL externa</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12" id="upload_wrapper">
                                                    <div class="form-group">
                                                        <label for="media">Archivo</label>
                                                        <input type="file" name="media" id="media" class="form-control" accept="image/*,video/mp4">
                                                    </div>
                                                </div>

                                                <div class="col-12" id="url_wrapper" style="display:none;">
                                                    <div class="form-group">
                                                        <label for="media_url">URL externa</label>
                                                        <input type="url" name="media_url" id="media_url" class="form-control" placeholder="https://ejemplo.com/archivo.jpg" value="{{ old('media_url') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <input type="hidden" name="position_x" id="position_x" value="{{ old('position_x', 50) }}">
                                                    <input type="hidden" name="position_y" id="position_y" value="{{ old('position_y', 50) }}">
                                                    <div class="slider-preview-wrapper" id="slider_preview_wrapper">
                                                        <div class="slider-preview" id="slider_preview" data-initial-x="{{ old('position_x', 50) }}" data-initial-y="{{ old('position_y', 50) }}">
                                                            <div class="slider-preview-overlay">Arrastra para ajustar la posición</div>
                                                        </div>
                                                    </div>
                                                </div>
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
    <script>
        $(function () {
            var $mediaInput = $('#media');
            var $mediaUrl = $('#media_url');
            var $uploadWrapper = $('#upload_wrapper');
            var $urlWrapper = $('#url_wrapper');
            var $previewWrapper = $('#slider_preview_wrapper');
            var $preview = $('#slider_preview');
            var $posX = $('#position_x');
            var $posY = $('#position_y');
            var $helper = $preview.find('.slider-preview-overlay');
            var dragging = false;
            var activePointerId = null;

            function clamp(v){ var n=parseFloat(v); if(isNaN(n)) n=50; return Math.max(0, Math.min(100, n)); }
            function updatePosition(x,y){ var cx=Math.round(clamp(x)); var cy=Math.round(clamp(y)); $preview.css('background-position', cx+'% '+cy+'%'); $posX.val(cx); $posY.val(cy); }
            function setPreviewImage(url,x,y){ if(!url){ clearPreview(); return; } $preview.html(''); $preview.css({'background-image':'url("'+url+'")','background-size':'cover'}).addClass('has-image').removeClass('no-helper'); updatePosition(typeof x==='undefined'?$posX.val():x, typeof y==='undefined'?$posY.val():y); showPreview(); }
            function setPreviewVideo(url){ $preview.html('<video src="'+url+'" class="w-100 h-100" style="object-fit: cover; height:100%;" autoplay muted loop playsinline></video>'); $preview.css({'background-image':'none'}).addClass('has-image').removeClass('no-helper'); showPreview(); }
            function setPreviewIframe(url){
                // extract youtube id if present
                var youtubeMatch = String(url).match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/);
                if(youtubeMatch){
                    var id = youtubeMatch[1];
                    var iframe = '<iframe class="w-100 h-100" src="https://www.youtube.com/embed/'+id+'?autoplay=1&mute=1&loop=1&playlist='+id+'&playsinline=1" frameborder="0" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen style="border:0;"></iframe>';
                    $preview.html(iframe);
                    $preview.css({'background-image':'none'}).addClass('has-image').removeClass('no-helper');
                    showPreview();
                } else {
                    // fallback to video tag
                    setPreviewVideo(url);
                }
            }
            function clearPreview(){ $preview.css('background-image','').removeClass('has-image is-dragging no-helper'); $preview.html(''); $helper.text('Arrastra para ajustar la posición'); $previewWrapper.hide(); }
            function showPreview(){ if ($preview.hasClass('has-image')) { $previewWrapper.stop(true,true).fadeIn(150); } else { $previewWrapper.hide(); } }
            function pointerToPosition(event){
                if(!$preview.hasClass('has-image')) return;
                // allow positioning for image and local video; skip for iframe (YouTube)
                if($preview.find('iframe').length) return;
                var original=event.originalEvent||event; var rect=$preview[0].getBoundingClientRect(); var xPercent=((original.clientX-rect.left)/rect.width)*100; var yPercent=((original.clientY-rect.top)/rect.height)*100;
                updatePosition(xPercent,yPercent);
                // if preview contains a video element, update its object-position
                var $vid = $preview.find('video');
                if($vid.length){ $vid.css('object-position', Math.round(xPercent)+'% '+Math.round(yPercent)+'%'); }
            }

            function setSourceMode(mode){ if(mode==='upload'){ $uploadWrapper.show(); $urlWrapper.hide(); } else { $uploadWrapper.hide(); $urlWrapper.show(); } }

            // initial source mode
            setSourceMode($('input[name="media_source"]:checked').val());

            // toggle source
            $('input[name="media_source"]').on('change', function(){ setSourceMode($(this).val()); if($(this).val()==='url'){ var url=$mediaUrl.val(); if(url){ applyUrlPreview(url); } } else { var f=$mediaInput[0].files[0]; if(f){ handleFilePreview(f); } }
            });

            function applyUrlPreview(url){ var type = $('#media_type').val(); if(!url) { clearPreview(); return; }
                if(type==='video'){
                    var youtubeMatch = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/);
                    if(youtubeMatch){ setPreviewIframe(url); return; }
                    // fallback to direct video preview
                    setPreviewVideo(url);
                } else {
                    setPreviewImage(url,50,50);
                }
            }

            function handleFilePreview(file){ if(!file){ clearPreview(); return; } if(file.type.startsWith('image/')){ var reader=new FileReader(); reader.onload=function(ev){ setPreviewImage(ev.target.result,50,50); }; reader.readAsDataURL(file); } else if(file.type.startsWith('video/')){ var vurl=URL.createObjectURL(file); setPreviewVideo(vurl); } else { clearPreview(); } }

            $mediaInput.on('change', function(e){ var file=e.target.files[0]; handleFilePreview(file); });
            $mediaUrl.on('input', function(){ var url=$(this).val().trim(); applyUrlPreview(url); });

            $preview.on('pointerdown', function(event){ if(!$preview.hasClass('has-image')) return; if($preview.find('video').length) return; dragging=true; activePointerId=event.originalEvent.pointerId; $preview.addClass('is-dragging'); pointerToPosition(event); event.preventDefault(); });
            $(document).on('pointermove', function(event){ if(!dragging || event.originalEvent.pointerId!==activePointerId) return; pointerToPosition(event); });
            $(document).on('pointerup pointercancel', function(event){ if(!dragging || event.originalEvent.pointerId!==activePointerId) return; dragging=false; activePointerId=null; $preview.removeClass('is-dragging').addClass('no-helper'); });

            // when media type changes, try to update preview if using URL
            $('#media_type').on('change', function(){ var url=$mediaUrl.val().trim(); if($('input[name="media_source"]:checked').val()==='url' && url){ applyUrlPreview(url); } });

            // initialize preview if old url present
            var initialUrl = $('#media_url').val() || '';
            if(initialUrl){ $('input[name="media_source"][value="url"]').prop('checked', true); setSourceMode('url'); applyUrlPreview(initialUrl); }

            showPreview();
        });
    </script>
@endsection
