@push('styles')
    <!-- Slick CSS -->
    <link rel="stylesheet" href="{{asset('assets/website/plugins/slick/slick.css')}}">
    <!-- Slider custom CSS -->
    <link href="{{ asset('assets/website/css/components/slider/slider.css') }}" rel="stylesheet">
    <style>
        .news-carousel-item {
            padding: 0 15px;
        }
        
        .news-cards {
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .news-cards:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .news-cards .card-img {
            height: 200px;
            overflow: hidden;
        }
        
        .news-cards .card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .news-cards .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .news-cards .card-text {
            flex: 1;
            margin-bottom: 15px;
        }
        
        /* Estilos para los controles del carrusel */
        .news-carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            transition: background 0.3s ease;
        }
        
        .news-carousel-control:hover {
            background: rgba(0, 0, 0, 0.8);
        }
        
        .news-carousel-control.prev {
            left: -20px;
        }
        
        .news-carousel-control.next {
            right: -20px;
        }
        
        .news-carousel-control .icon-arrow-btn {
            font-size: 20px;
        }
        
        /* Contenedor del carrusel */
        .news-carousel-wrapper {
            position: relative;
            padding: 0 40px;
        }
        
        /* Indicadores del carrusel */
        .news-carousel-indicators {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        .news-carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #333;
            background: transparent;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .news-carousel-indicators button.active {
            background: #333;
        }
    </style>
@endpush

@if (isset($latestsPosts) && count($latestsPosts) > 0)
    <section class="related-projects section-sm bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <h2>Noticias y Actividades para ti</h2>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="news-carousel-wrapper">
                        <div id="newsSliderCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @php
                                    $itemsPerSlide = 3;
                                    $posts = $latestsPosts->values();
                                    $totalPosts = $posts->count();

                                    $slides = [];

                                    if ($totalPosts > 0) {
                                        $slideCount = (int) ceil($totalPosts / $itemsPerSlide);

                                        if ($slideCount <= 2) {
                                            $slides = $posts->chunk($itemsPerSlide)
                                                ->map(fn($chunk) => $chunk->all())
                                                ->all();
                                        } else {
                                            for ($i = 0; $i < $slideCount; $i++) {
                                                $slideItems = [];

                                                for ($j = 0; $j < $itemsPerSlide; $j++) {
                                                    $index = ($i * $itemsPerSlide + $j) % $totalPosts;
                                                    $slideItems[] = $posts->get($index);
                                                }

                                                $slides[] = $slideItems;
                                            }
                                        }
                                    }
                                @endphp

                                @foreach ($slides as $index => $slide)
                                    <div class="carousel-item @if($index == 0) active @endif">
                                        <div class="row">
                                            @foreach ($slide as $latestPost)
                                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                    <div class="card news-cards">
                                                        <div class="card-img">
                                                            <img class="card-img-top"
                                                                 src="{{ $latestPost->gallery->image ?? asset('assets/website/images/default-news.jpg') }}"
                                                                 alt="{{ $latestPost->title }}">
                                                        </div>
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $latestPost->title }}</h5>
                                                            <div class="card-text">
                                                                {!! limitHtml($latestPost->description, 80, '...') !!}
                                                            </div>
                                                            <a href="{{ route('news.single', $latestPost->id) }}"
                                                               class="btn btn-primary">Saber Más</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Controles del carrusel -->
                            @if(count($slides) > 1)
                                <button class="news-carousel-control prev" type="button" data-target="#newsSliderCarousel" data-slide="prev">
                                    <span class="ion-arrow-left-a icon-arrow-btn" aria-hidden="true"></span>
                                </button>
                                <button class="news-carousel-control next" type="button" data-target="#newsSliderCarousel" data-slide="next">
                                    <span class="ion-arrow-right-a icon-arrow-btn" aria-hidden="true"></span>
                                </button>
                            @endif
                        </div>

                        <!-- Indicadores -->
                        @if(count($slides) > 1)
                            <div class="news-carousel-indicators">
                                @foreach ($slides as $index => $slide)
                                    <button type="button"
                                            data-target="#newsSliderCarousel"
                                            data-slide-to="{{ $index }}"
                                            class="@if($index == 0) active @endif"
                                            aria-label="Slide {{ $index + 1 }}">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@push('scripts')
    <!-- slick Carousel -->
    <script src="{{ asset('assets/website/plugins/slick/slick.min.js') }}"></script>
    <!-- Slider custom JS -->
    <script src="{{ asset('assets/website/js/components/slider/slider.js') }}"></script>
    
    <script>
        $(function () {
            var $newsCarousel = $('#newsSliderCarousel');
            
            if ($newsCarousel.length && typeof $newsCarousel.carousel === 'function') {
                // Inicializar el carrusel con auto-play
                $newsCarousel.carousel({
                    interval: 5000,  // Cambia cada 5 segundos
                    pause: 'hover',  // Pausa cuando el mouse está encima
                    wrap: true       // Vuelve al inicio después del último slide
                });
                
                // Sincronizar los indicadores personalizados
                $('.news-carousel-indicators button').on('click', function() {
                    var slideTo = $(this).data('slide-to');
                    $newsCarousel.carousel(slideTo);
                });
                
                // Actualizar indicadores cuando cambia el slide
                $newsCarousel.on('slide.bs.carousel', function (e) {
                    var index = $(e.relatedTarget).index();
                    $('.news-carousel-indicators button').removeClass('active');
                    $('.news-carousel-indicators button').eq(index).addClass('active');
                });
            }
        });
    </script>
@endpush