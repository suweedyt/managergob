@php
    $ns = $settings ?? null;
    $itemsToShow = $items->shuffle(); // orden aleatorio
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/website/css/components/news-showcase.css') }}">
@endpush

<section class="news-showcase py-5">
    <div class="container">
        @if($ns && ($ns->title || $ns->subtitle))
            <div class="row mb-3">
                <div class="col-12 text-center">
                    @if($ns->title)
                        <h2 class="mb-1">{{ $ns->title }}</h2>
                    @endif
                    @if($ns->subtitle)
                        <p class="lead mb-0">{{ $ns->subtitle }}</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="news-showcase-grid">
            @foreach($itemsToShow as $item)
                @php
                    $post = $item->post;
                    $img = optional($post->gallery)->image ?? asset('assets/website/images/default-news.jpg');
                    $cls = $item->is_large ? 'large' : 'small';
                @endphp

                <div class="showcase-item {{ $cls }}">
                    <a href="{{ route('news.single', $post->id) }}" class="showcase-link d-block h-100">
                        <div class="showcase-image" style="background-image:url('{{ $img }}');"></div>
                        <div class="showcase-overlay">
                            <span class="showcase-title">{{ $post->title }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>