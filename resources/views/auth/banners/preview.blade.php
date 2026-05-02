@extends('layouts.auth')

@section('title', 'Preview Banner')

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Vista previa: {{ $title }} </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('banners.index') }}">Banners</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Preview Banner</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="header-subsection">
                            <h4 class="card-title">{{ $title }}</h4>
                            <div>
                                <a href="{{ route('banners.index') }}" id="btn-back-to-list" class="btn btn-sm btn-outline-secondary">← Volver a la lista</a>
                            </div>
                        </div>

                        <div class="body-subsection" style="margin-top: 5%; height: 40vh;">
                            @if($mediaType === 'image')
                                <div style="height: 100%; background-image: url('{{ (str_starts_with($mediaPath, 'http') ? $mediaPath : asset(ltrim($mediaPath, '/'))) }}'); background-size: cover; background-position: {{ $positionX }}% {{ $positionY }}%;"></div>
                            @elseif($mediaType === 'video')
                                @php
                                    $isExternal = \Illuminate\Support\Str::startsWith($mediaPath ?? '', ['http://','https://']);
                                    $isYouTube = $isExternal && preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/', $mediaPath, $m) ? ($m[1] ?? null) : null;
                                @endphp

                                @if($isYouTube)
                                    <iframe width="100%" height="100%"; src="https://www.youtube.com/embed/{{ $isYouTube }}?autoplay=1&mute=1&playsinline=1" frameborder="0" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen style="border:0;"></iframe>
                                @else
                                    <video controls autoplay muted loop style="width:100%; height: 100%; object-fit:cover; object-position: {{ $positionX }}% {{ $positionY }}%;">
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
    </div>
@endsection
