<div class="post post-single admin-preview">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h2 class="post-title">{{ $post->title }}</h2>
        <div>
            <button type="button" id="btn-back-to-list" class="btn btn-sm btn-outline-secondary">← Volver a la lista</button>
        </div>
    </div>

    <div class="post-meta mb-2">
        <ul class="list-inline">
            <li class="list-inline-item"><i class="ion-calendar"></i> {{ date('d M Y', strtotime($post->created_at)); }}</li>
            <li class="list-inline-item"><i class="ion-android-folder"></i> {{ $post->category?->name ?? '' }}</li>
        </ul>
    </div>

    <div class="post-thumb text-center mb-3">
        @if(optional($post->sliderGallery)->image)
            <img class="img-fluid" src="{{ asset(optional($post->sliderGallery)->image) }}" alt="">
        @elseif(optional($post->gallery)->image)
            <img class="img-fluid" src="{{ asset(optional($post->gallery)->image) }}" alt="">
        @endif
    </div>

    <div class="post-content post-excerpt">
        <p> {!! $post->description !!} </p>
    </div>
</div>
