@extends('layouts.website')

@section('title', $settings->title ?? 'Destacado')

@section('content')
<section class="section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="mb-3">{{ $settings->title }}</h1>
                @if($settings->subtitle)
                    <p class="lead">{{ $settings->subtitle }}</p>
                @endif
                @if($settings->landing_image)
                    <div class="mb-4 text-center">
                        <img src="{{ asset($settings->landing_image) }}" alt="Imagen destacada" class="img-fluid" style="max-height:360px; object-fit:cover;">
                    </div>
                @endif
                <div class="content">
                    {!! $settings->landing_content !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
