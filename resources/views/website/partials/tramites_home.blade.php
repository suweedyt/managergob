<section class="service">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="section-title">
                    <h2>{{ optional($tramiteSettings)->title ?? 'Trámites' }}</h2>
                    <p>
                        {{ optional($tramiteSettings)->subtitle ?? 'Encuentra los trámites más solicitados. Haz clic en cada uno para ver los requisitos y pasos.' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($tramites as $tramite)
                <div class="col-lg-3 col-md-4 col-sm-6">
                        @php $tramiteUrl = $tramite->mode === 'link' && $tramite->redirect_url ? $tramite->redirect_url : url('/tramites/' . $tramite->id); @endphp
                            <a href="{{ $tramiteUrl }}" class="service-item d-block text-decoration-none text-reset" @if($tramite->mode === 'link' && $tramite->redirect_url) target="_blank" rel="noopener noreferrer" @endif>
                        @if($tramite->logo_image)
                            <img src="{{ asset($tramite->logo_image) }}" alt="{{ $tramite->title_short }}" style="width:48px; height:48px; object-fit:contain; display:block; margin:0 auto 8px;">
                        @elseif($tramite->logo_class)
                            <i class="{{ $tramite->logo_class }}" style="font-size:28px; display:block; text-align:center; margin-bottom:8px;"></i>
                        @else
                            <i class="ion-clipboard" style="font-size:28px; display:block; text-align:center; margin-bottom:8px;"></i>
                        @endif
                        <h4 class="text-center">{{ $tramite->title_short }}</h4>
                        <p class="text-center">{{ Str::limit($tramite->description, 100) }}</p>
                    </a>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-info">No hay trámites publicados.</div></div>
            @endforelse
        </div>
    </div>
</section>
