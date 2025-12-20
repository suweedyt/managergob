@extends('layouts.website')

@section('title', 'Trámites')

@section('content')

    <div class="page-wrapper">
        <div class="container py-4">
            <div class="row">
                <h1>{{ optional($tramiteSettings)->title ?? 'Trámites' }}</h1>
                @if(optional($tramiteSettings)->subtitle)
                    <p class="lead">{{ $tramiteSettings->subtitle }}</p>
                @endif
            </div>

            <div class="accordion" id="tramitesAccordion">
                @forelse($tramites as $tramite)
                    <div class="card">
                        <div class="card-header" id="heading-{{ $tramite->id }}">
                            <h2 class="mb-0">
                                    @if($tramite->mode === 'link' && $tramite->redirect_url)
                                        {{-- Open external link (or PDF) in a new tab instead of an internal viewer --}}
                                        <a class="btn btn-link d-flex align-items-center" href="{{ $tramite->redirect_url }}" target="_blank" rel="noopener noreferrer">
                                    @else
                                        <button class="btn btn-link d-flex align-items-center" type="button" data-toggle="collapse" data-target="#collapse-{{ $tramite->id }}" aria-expanded="false" aria-controls="collapse-{{ $tramite->id }}">
                                    @endif
                                    @if($tramite->logo_image)
                                        <img src="{{ asset($tramite->logo_image) }}" alt="logo" style="max-width:40px; margin-right:10px;">
                                    @elseif($tramite->logo_class)
                                        <i class="{{ $tramite->logo_class }}" style="font-size:20px; margin-right:10px;"></i>
                                    @endif
                                    <strong>{{ $tramite->title_full }}</strong>
                                    @if($tramite->mode === 'link' && $tramite->redirect_url)
                                        </a>
                                    @else
                                        </button>
                                    @endif
                            </h2>
                        </div>

                        <div id="collapse-{{ $tramite->id }}" class="collapse @if(isset($openId) && $openId == $tramite->id) show @endif" aria-labelledby="heading-{{ $tramite->id }}" data-parent="#tramitesAccordion">
                            <div class="card-body">
                                <p>{{ $tramite->description }}</p>
                                <div class="rich-content">{!! $tramite->content !!}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">No hay trámites publicados.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
