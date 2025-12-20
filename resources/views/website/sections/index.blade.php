@extends('layouts.website')

@section('title', 'Secciones')

@section('content')
    <div class="page-wrapper">
        <div class="container py-4">
            <div class="accordion" id="sectionsAccordion">
                @forelse($sections as $section)
                    <div class="card">
                        <div class="card-header" id="heading-{{ $section->id }}">
                            <h2 class="mb-0">
                                @if($section->mode === 'link' && $section->redirect_url)
                                    <a class="btn btn-link d-flex align-items-center" href="{{ $section->redirect_url }}" target="{{ str_ends_with(strtolower($section->redirect_url), '.pdf') ? '_self' : '_blank' }}" rel="noopener noreferrer">
                                @else
                                    <button class="btn btn-link d-flex align-items-center" type="button" data-toggle="collapse" data-target="#collapse-{{ $section->id }}" aria-expanded="false" aria-controls="collapse-{{ $section->id }}">
                                @endif
                                @if($section->logo_image)
                                    <img src="{{ asset($section->logo_image) }}" alt="logo" style="max-width:40px; margin-right:10px;">
                                @elseif($section->logo_class)
                                    <i class="{{ $section->logo_class }}" style="font-size:20px; margin-right:10px;"></i>
                                @endif
                                <strong>{{ $section->title_full }}</strong>
                                @if($section->mode === 'link' && $section->redirect_url)
                                    </a>
                                @else
                                    </button>
                                @endif
                            </h2>
                        </div>

                        <div id="collapse-{{ $section->id }}" class="collapse @if(isset($collapse) && $collapse == $section->id) show @endif" aria-labelledby="heading-{{ $section->id }}" data-parent="#sectionsAccordion">
                            <div class="card-body">
                                @if($section->description)
                                    <p>{{ $section->description }}</p>
                                @endif
                                <div class="rich-content">{!! $section->content !!}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">No hay secciones disponibles.</div>
                @endforelse
            </div>
        </div>
    </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function(){
                                    // If URL contains fragment like #collapse-2, open that panel (fallback when coming from anchors)
                                    try {
                                        var hash = window.location.hash || '';
                                        if(hash.indexOf('#collapse-') === 0) {
                                            var id = hash.replace('#collapse-','');
                                            var selector = '#collapse-' + id;
                                            var el = document.querySelector(selector);
                                            if(el) {
                                                if(window.jQuery && typeof jQuery(el).collapse === 'function'){
                                                    jQuery(el).collapse('show');
                                                } else {
                                                    el.classList.add('show');
                                                }
                                                setTimeout(function(){ el.scrollIntoView({behavior: 'smooth', block: 'center'}); }, 120);
                                            }
                                        }
                                    } catch(e) { console && console.warn && console.warn(e); }
                                });
                            </script>
                @endsection
