@push('styles')
    <!-- Slider Banners custom CSS -->
    <link href="{{ asset('assets/website/css/components/slider_banners/style.css') }}" rel="stylesheet">
@endpush

@if (isset($bannerSliders) && count($bannerSliders) > 0)
    <section class="slider">
        <div id="newsCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($bannerSliders as $index => $post)
                    @php
                        $image = null;
                        if (optional($post->sliderGallery)->image) {
                            $image = asset($post->sliderGallery->image);
                        } elseif (optional($post->gallery)->image) {
                            $image = asset($post->gallery->image);
                        } else {
                            $image = asset('assets/website/images/nosotrosGobierno.jpg');
                        }

                        // Ensure slider positions are numeric and within 0-100, default to center (50%)
                        $positionX = is_numeric($post->slider_position_x) ? max(0, min(100, (int) $post->slider_position_x)) : 50;
                        $positionY = is_numeric($post->slider_position_y) ? max(0, min(100, (int) $post->slider_position_y)) : 50;

                        $bannerDescription = $post->banner_short_description
                            ? $post->banner_short_description
                            : limitHtml($post->description, 150, '...');
                    @endphp

                    <div class="carousel-item @if($index == 0) active @endif">
                        <div class="d-block w-100 carousel-slide" style="background-image: url('{{ $image }}'); background-size: cover; background-position: {{ $positionX }}% {{ $positionY }}%; height: 520px;">
                            <div class="carousel-caption d-none d-md-block text-start" style="background: rgba(0,0,0,0.4); padding: 20px;">
                                <h3>{{ $post->title }}</h3>
                                <p class="shortDescription-banner">{!! $bannerDescription !!}</p>
                                <a href="{{ route('news.single', $post->id) }}" class="btn btn-primary">Leer más</a>
                            </div>
                        </div>
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
            }
        });
    </script>
@endpush
