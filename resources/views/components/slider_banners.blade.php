@push('styles')
    <!-- Slider Banners custom CSS -->
    <link href="{{ asset('assets/website/css/components/slider_banners/style.css') }}" rel="stylesheet">
@endpush

@if (isset($bannerSliders) && count($bannerSliders) > 0)
    <section class="slider">
        <div id="newsCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($bannerSliders as $index => $item)
                    @php
                        $isPost = $item instanceof \App\Models\Post;
                        $title = $isPost ? $item->title : $item->title;
                        // for posts allow admin to override button text/color and control caption visibility
                        $buttonText = $isPost ? ($item->banner_button_text ?: 'Leer más') : ($item->button_text ?: 'Ver más');
                        $buttonUrl = $isPost ? route('news.single', $item->id) : ($item->button_url ?: '#');
                        $buttonBg = $isPost ? ($item->banner_button_bg_color ?: '#0d6efd') : ($item->button_bg_color ?: '#0d6efd');

                        if ($isPost) {
                            if (optional($item->sliderGallery)->image) {
                                $image = asset($item->sliderGallery->image);
                            } elseif (optional($item->gallery)->image) {
                                $image = asset($item->gallery->image);
                            } else {
                                $image = asset('assets/website/images/nosotrosGobierno.jpg');
                            }

                            $positionX = is_numeric($item->slider_position_x) ? max(0, min(100, (int) $item->slider_position_x)) : 50;
                            $positionY = is_numeric($item->slider_position_y) ? max(0, min(100, (int) $item->slider_position_y)) : 50;
                            $bannerDescription = $item->banner_short_description ? $item->banner_short_description : limitHtml($item->description, 150, '...');
                            $isVideo = false;
                        } else {
                            $isVideo = $item->media_type === 'video';
                            $image = asset(ltrim($item->media_path, '/'));
                            $positionX = is_numeric($item->position_x) ? max(0, min(100, (int) $item->position_x)) : 50;
                            $positionY = is_numeric($item->position_y) ? max(0, min(100, (int) $item->position_y)) : 50;
                            $bannerDescription = $item->short_description ?: limitHtml($item->long_description, 150, '...');
                        }
                    @endphp

                    <div class="carousel-item @if($index == 0) active @endif">
                        @if($isVideo)
                            @php
                                $mediaRaw = $item->media_path;
                                $isExternal = \Illuminate\Support\Str::startsWith($mediaRaw, ['http://','https://']);
                                $isYouTube = $isExternal && preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/', $mediaRaw, $m) ? ($m[1] ?? null) : null;
                                $mediaSrc = $isExternal ? $mediaRaw : asset(ltrim($mediaRaw, '/'));
                            @endphp

                            <div class="d-block w-100 carousel-slide" style="height: 520px; background: #000; display:flex; align-items:center; justify-content:center;">
                                    @if($isYouTube)
                                    @php $ytId = $isYouTube; @endphp
                                    <iframe class="w-100 h-100" src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=1&mute=1&loop=1&playlist={{ $ytId }}&playsinline=1" frameborder="0" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen style="border:0; pointer-events: none;" ></iframe>
                                @else
                                    <video id="slider-video-{{ $index }}" src="{{ $mediaSrc }}" class="w-100 h-100 slider-video" style="object-fit: cover; object-position: {{ $positionX }}% {{ $positionY }}%; pointer-events: none;" playsinline muted loop preload="metadata"></video>
                                @endif
                            </div>
                        @else
                            <div class="d-block w-100 carousel-slide" style="background-image: url('{{ $image }}'); background-size: cover; background-position: {{ $positionX }}% {{ $positionY }}%; height: 520px;">
                            </div>
                        @endif
                        @php
                            // Decide whether to show caption:
                            // - for posts: respect `banner_show_caption` (default true)
                            // - for admin banners: show only when both button_text and button_url provided
                            $showCaption = $isPost ? (($item->banner_show_caption ?? true) ? true : false) : (!empty($item->button_text) && !empty($item->button_url));
                        @endphp

                        @if($isPost)
                            @if($item->banner_show_caption ?? true)
                                <div class="carousel-caption d-none d-md-block text-start" style="background: rgba(0,0,0,0.4); padding: 20px;">
                                    <h3>{{ $title }}</h3>
                                    <p class="shortDescription-banner">{!! $bannerDescription !!}</p>
                                    <a href="{{ $buttonUrl }}" class="btn btn-primary" style="background-color: {{ $buttonBg }}; border-color: {{ $buttonBg }};" target="{{ $isPost ? '_self' : '_blank' }}">{{ $buttonText }}</a>
                                </div>
                            @else
                                <div class="carousel-caption d-none d-md-block text-start" style="background: rgba(0,0,0,0.0); padding: 20px;">
                                    <a href="{{ $buttonUrl }}" class="btn btn-primary" style="background-color: {{ $buttonBg }}; border-color: {{ $buttonBg }};" target="{{ $isPost ? '_self' : '_blank' }}">{{ $buttonText }}</a>
                                </div>
                            @endif
                        @else
                            @if(!empty($item->button_text) && !empty($item->button_url))
                                <div class="carousel-caption d-none d-md-block text-start" style="background: rgba(0,0,0,0.4); padding: 20px;">
                                    <h3>{{ $title }}</h3>
                                    <p class="shortDescription-banner">{!! $bannerDescription !!}</p>
                                    @if($isVideo)
                                        @if(!$isExternal && !$isYouTube)
                                            <a href="{{ $buttonUrl }}" class="btn btn-primary btn-config" style="background-color: {{ $buttonBg }}; border-color: {{ $buttonBg }};" target="{{ $isPost ? '_self' : '_blank' }}">{{ $buttonText }}</a>
                                            <button type="button" class="btn btn-secondary btn-video-action" data-video-local="true" style="margin-left:8px;">Reproducir</button>
                                        @else
                                            <a href="{{ $buttonUrl }}" class="btn btn-primary" style="background-color: {{ $buttonBg }}; border-color: {{ $buttonBg }};" target="{{ $isPost ? '_self' : '_blank' }}">{{ $buttonText }}</a>
                                        @endif
                                    @else
                                        <a href="{{ $buttonUrl }}" class="btn btn-primary" style="background-color: {{ $buttonBg }}; border-color: {{ $buttonBg }};" target="{{ $isPost ? '_self' : '_blank' }}">{{ $buttonText }}</a>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev arrow-btn-nav" type="button" data-target="#newsCarousel" data-slide="prev">
                <span class="ion-arrow-left-a icon-arrow-btn" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next arrow-btn-nav" type="button" data-target="#newsCarousel" data-slide="next">
                <span class="ion-arrow-right-a icon-arrow-btn" aria-hidden="true"></span>
            </button>
        </div>
    </section>
@endif

@push('scripts')
    <script>
        // Ensure carousel is initialized (Bootstrap 4 uses jQuery plugin)
        $(function () {
            var $carousel = $('#newsCarousel');
            if ($carousel.length && typeof $carousel.carousel === 'function') {
                // initialize with sensible defaults (5s interval)
                $carousel.carousel({ interval: 5000, pause: 'hover' });
                // Pause all videos initially except active
                function pauseAll() {
                    $carousel.find('video').each(function() { try{ this.pause(); }catch(e){} });
                }

                function playActive() {
                    var $active = $carousel.find('.carousel-item.active');
                    var vid = $active.find('video').get(0);
                    if(vid){ try{ vid.currentTime = 0; vid.muted = true; vid.play().catch(()=>{}); }catch(e){} }
                }

                // on slide event, pause others and play active after transition
                $carousel.on('slide.bs.carousel', function () {
                    pauseAll();
                });

                $carousel.on('slid.bs.carousel', function () {
                    playActive();
                });

                // button on active slide: toggle play/pause for local videos
                $(document).on('click', '.btn-video-action', function(e){
                    var $btn = $(this);
                    var $active = $carousel.find('.carousel-item.active');
                    var vid = $active.find('video').get(0);

                    // If button is intended to control a local video and a video element exists
                    if(vid && $btn.data('video-local')){
                        e.preventDefault();
                        try{
                            if(vid.paused){ vid.play(); $btn.addClass('playing').text('Pausar'); }
                            else { vid.pause(); $btn.removeClass('playing').text('Reproducir'); }
                        }catch(err){
                            // fallback: do nothing or optionally navigate
                        }
                        return;
                    }

                    // Otherwise, let link buttons behave normally (navigation)
                });

                // initial state
                pauseAll();
                playActive();
            }
        });
    </script>
@endpush
