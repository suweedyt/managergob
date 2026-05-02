@extends('layouts.website')

@section('styles')
<style>
    .location-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        border: none;
        overflow: hidden;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 8px 24px rgba(20, 24, 62, 0.08);
    }
    .location-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(20, 24, 62, 0.12);
    }
    .location-map {
        position: relative;
        width: 100%;
        height: 340px;
        border-radius: 1rem 1rem 0 0;
        overflow: hidden;
        background-color: #0f172a;
        background-repeat: no-repeat;
        background-size: 210%;
        background-position: 50% 50%;
        cursor: grab;
        user-select: none;
        touch-action: none;
        transition: background-position 0.25s ease, background-size 0.35s ease;
    }
    .location-map.dragging {
        cursor: grabbing;
        transition: none;
    }
    .location-map::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(255,255,255,0) 0%, rgba(15,23,42,0.22) 65%);
        pointer-events: none;
    }
    .location-map-fallback {
        height: 340px;
    }
    .location-info {
        padding: 1.75rem;
    }
    .location-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1f2937;
    }
    .location-address {
        color: #6b7280;
        font-size: 0.95rem;
    }
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0 3rem;
        margin-bottom: 3rem;
        border-radius: 0 0 2.5rem 2.5rem;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="display-4 fw-bold mb-2">{{ optional($contactSettings)->title ?? 'Ubicaciones' }}</h1>
        <p class="lead mb-0">{{ optional($contactSettings)->subtitle ?? 'Encuentra nuestras oficinas y puntos de atención' }}</p>
    </div>
</div>

<div class="container mb-5 mt-5">
    @if($locations->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            No hay ubicaciones disponibles en este momento.
        </div>
    @else
        <div class="row g-4">
            @foreach($locations as $location)
                @php
                    $latitude = $location->latitude;
                    $longitude = $location->longitude;
                    $googleKey = optional($contactSettings)->google_maps_api_key;
                    $mapUrl = null;

                    if (!empty($latitude) && !empty($longitude)) {
                        if (!empty($googleKey)) {
                            $mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center=' . $latitude . ',' . $longitude . '&zoom=18&size=640x360&scale=2&markers=color:red|' . $latitude . ',' . $longitude . '&key=' . $googleKey;
                        } else {
                            $mapUrl = 'https://staticmap.openstreetmap.de/staticmap.php?center=' . $latitude . ',' . $longitude . '&zoom=18&size=640x360&markers=' . $latitude . ',' . $longitude . ',red-pushpin';
                        }
                    }
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card location-card">
                        @if($mapUrl)
                            <div class="location-map w-100" data-map-url="{{ $mapUrl }}" style="background-image: url('{{ $mapUrl }}'); height: 300px;"></div>
                        @else
                            <div class="location-map location-map-fallback d-flex align-items-center justify-content-center bg-light text-muted w-100">
                                @if(!empty($latitude) && !empty($longitude))
                                    <div class="text-center">
                                        <p class="mb-2">Mapa no disponible</p>
                                        <small>Coordenadas: {{ $latitude }}, {{ $longitude }}</small>
                                    </div>
                                @else
                                    Coordenadas no disponibles
                                @endif
                            </div>
                        @endif
                        <div class="location-info">
                            <h5 class="location-title mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                {{ $location->name }}
                            </h5>
                            <p class="location-address mb-4">
                                <i class="fas fa-location-dot me-2 text-primary"></i>
                                {{ $location->address }}
                            </p>
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $location->latitude }},{{ $location->longitude }}"
                               target="_blank"
                               class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-directions me-2"></i>
                                Cómo llegar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

        document.querySelectorAll('.location-map[data-map-url]').forEach((container) => {
            let isDragging = false;
            let startX = 0;
            let startY = 0;
            let posX = 50;
            let posY = 50;

            const applyPosition = () => {
                container.style.backgroundPosition = `${posX}% ${posY}%`;
            };

            const startDrag = (x, y) => {
                isDragging = true;
                startX = x;
                startY = y;
                container.classList.add('dragging');
            };

            const updateDrag = (x, y) => {
                if (!isDragging) return;

                const rect = container.getBoundingClientRect();
                const percentX = ((x - startX) / rect.width) * 100;
                const percentY = ((y - startY) / rect.height) * 100;

                startX = x;
                startY = y;

                posX = clamp(posX - percentX, 0, 100);
                posY = clamp(posY - percentY, 0, 100);

                applyPosition();
            };

            const stopDrag = () => {
                if (!isDragging) return;
                isDragging = false;
                container.classList.remove('dragging');
            };

            container.addEventListener('mousedown', (event) => {
                startDrag(event.clientX, event.clientY);
            });
            window.addEventListener('mousemove', (event) => {
                updateDrag(event.clientX, event.clientY);
            });
            window.addEventListener('mouseup', stopDrag);
            container.addEventListener('mouseleave', stopDrag);

            container.addEventListener('touchstart', (event) => {
                const touch = event.touches[0];
                startDrag(touch.clientX, touch.clientY);
            }, { passive: true });
            container.addEventListener('touchmove', (event) => {
                const touch = event.touches[0];
                updateDrag(touch.clientX, touch.clientY);
            }, { passive: true });
            container.addEventListener('touchend', stopDrag, { passive: true });
            container.addEventListener('touchcancel', stopDrag, { passive: true });

            applyPosition();
        });
    });
</script>
@endpush
