<section class="service">
    <div class="container">
        @if (optional($sectionsSettings)->title)
            <div class="row">
                <div class="col-12 text-center">
                    <div class="section-title">
                        <h2>{{ optional($sectionsSettings)->title ?? 'Secciones' }}</h2>
                        <p>
                            {{ optional($sectionsSettings)->subtitle ?? 'Explora las secciones disponibles. Haz clic para ver más detalles.' }}
                        </p>
                    </div>
                </div>
            </div>
         @endif
        <div class="row">
            @forelse($sections as $section)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    @php
                        $url = $section->mode === 'link' && $section->redirect_url ? $section->redirect_url : url('/sections?collapse=' . $section->id);
                    @endphp
                    <a href="{{ $url }}" class="service-item d-block text-decoration-none text-reset" @if($section->mode === 'link' && $section->redirect_url && !str_contains(strtolower($section->redirect_url), request()->getHost())) target="_blank" rel="noopener noreferrer" @endif>

                        @if($section->logo_image)
                            <img src="{{ asset($section->logo_image) }}" alt="{{ $section->title_full }}" style="width:100px; height:50px; object-fit:contain; display:block; margin:0 auto 8px;">
                        @elseif($section->logo_class)
                            <i class="{{ $section->logo_class }}" style="font-size:28px; display:block; text-align:center; margin-bottom:8px;"></i>
                        @else
                            <i class="ion-clipboard" style="font-size:28px; display:block; text-align:center; margin-bottom:8px;"></i>
                        @endif

                        <h4 class="text-center">{{ $section->title_full }}</h4>
                        <p class="text-center">{{ Str::limit($section->title_short ?? $section->description, 100) }}</p>
                    </a>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-info">No hay secciones publicadas.</div></div>
            @endforelse
        </div>
    </div>
</section>
