@extends('layouts.auth')

@section('title', 'Preview Banner')

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Vista previa: {{ $title }} </h3>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        @if($mediaType === 'image')
                            <div style="height:80vh; background-image: url('{{ (str_starts_with($mediaPath, 'http') ? $mediaPath : asset(ltrim($mediaPath, '/'))) }}'); background-size: cover; background-position: {{ $positionX }}% {{ $positionY }}%;"></div>
                        @elseif($mediaType === 'video')
                            @php
                                $isExternal = \Illuminate\Support\Str::startsWith($mediaPath ?? '', ['http://','https://']);
                                $isYouTube = $isExternal && preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/', $mediaPath, $m) ? ($m[1] ?? null) : null;
                            @endphp

                            @if($isYouTube)
                                <iframe width="100%" height="80vh" src="https://www.youtube.com/embed/{{ $isYouTube }}?autoplay=1&mute=1&playsinline=1" frameborder="0" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen style="border:0;"></iframe>
                            @else
                                <video controls autoplay muted loop style="width:100%; height:80vh; object-fit:cover; object-position: {{ $positionX }}% {{ $positionY }}%;">
                                    <source src="{{ (str_starts_with($mediaPath, 'http') ? $mediaPath : asset(ltrim($mediaPath, '/'))) }}" type="video/mp4">
                                    Tu navegador no soporta video.
                                </video>
                            @endif
                        @else
                            <div class="p-4">No hay media para mostrar.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
