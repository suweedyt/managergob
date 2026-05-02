@extends('layouts.website')

@section('title', 'Trámites')

@section('content')

    <div class="page-wrapper">
        <div class="container py-4">
            <div class="row">
                <h1>{{ optional($tramiteSettings)->title ?? 'Trámites' }}</h1>
            </div>
            @if(optional($tramiteSettings)->subtitle)
                <div class="row">
                    <p class="lead">{{ $tramiteSettings->subtitle }}</p>
                </div>
            @endif

            <div class="row">
                <div class="col-md-9">
                    <div class="accordion" id="tramitesAccordion">
                        @forelse($categories ?? [] as $cat)
                            <div class="card mb-2 category-block" id="cat-{{ $cat->id }}">
                                <div class="card-header">
                                    <h2 class="mb-0">
                                        <strong>{{ $cat->name }}</strong>
                                    </h2>
                                </div>

                                <div class="card-body">
                                    @if($cat->tramites->count())
                                        <div class="accordion" id="accordion-cat-{{ $cat->id }}">
                                            @foreach($cat->tramites as $tramite)
                                                <div class="card">
                                                    <div class="card-header" id="heading-{{ $tramite->id }}">
                                                        <h2 class="mb-0">
                                                            @if($tramite->mode === 'link' && $tramite->redirect_url)
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

                                                    <div id="collapse-{{ $tramite->id }}" class="collapse @if(isset($openId) && $openId == $tramite->id) show @endif" aria-labelledby="heading-{{ $tramite->id }}" data-parent="#accordion-cat-{{ $cat->id }}">
                                                        <div class="card-body">
                                                            <p>{{ $tramite->description }}</p>
                                                            <div class="rich-content">{!! $tramite->content !!}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info mb-0">Sin trámites en esta categoría.</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">No hay categorías con trámites.</div>
                        @endforelse
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="list-group" id="categoryFilter">
                        <a href="#" class="list-group-item list-group-item-action active" data-category="all">Todas las categorías</a>
                        @foreach($categories ?? [] as $cat)
                            <a href="#" class="list-group-item list-group-item-action" data-category="cat-{{ $cat->id }}">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('#categoryFilter a');
        const blocks = document.querySelectorAll('.category-block');

        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                links.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                const target = this.dataset.category;
                blocks.forEach(block => {
                    if (target === 'all' || block.id === target) {
                        block.style.display = '';
                    } else {
                        block.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush
