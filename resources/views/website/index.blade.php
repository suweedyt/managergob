@extends ('layouts.website')

@section ('title', 'Bienvenido')

@section ('content')

    <!-- Slider Start -->
    @if(isset($bannerSliders) && count($bannerSliders) > 0)
        <x-slider_banners :bannerSliders="$bannerSliders" />
    @endif

    @include('website.partials.tramites_home', [
        'sections' => $sectionsHome ?? collect(),
        'sectionsSettings' => $sectionsSettings ?? null,
    ])

    @if(isset($showcaseItems) && $showcaseItems->count())
        <x-news-showcase :items="$showcaseItems" :settings="$showcaseSettings" />
    @endif

    <x-feature-section :featureSetting="$featureSetting ?? null" />

    <x-slider />

@endsection