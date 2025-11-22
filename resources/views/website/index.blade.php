@extends ('layouts.website')

@section ('title', 'Bienvenido')

@section ('content')

    <!-- Slider Start -->
    @if(isset($bannerSliders) && count($bannerSliders) > 0)
        <x-slider_banners :bannerSliders="$bannerSliders" />
    @endif

    @include('website.partials.tramites_home', ['tramites' => $tramites ?? collect(), 'tramiteSettings' => $tramiteSettings ?? null])

    <x-feature-section :featureSetting="$featureSetting ?? null" />

    <x-slider />

@endsection