@extends('layouts.auth')

@section('title', 'Nueva Ubicación')

@section('styles')
<style>
    #map {
        height: 400px;
        width: 100%;
        border-radius: 0.375rem;
        position: relative;
    }
    .map-loader {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(3px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        border-radius: 0.375rem;
    }
    .map-loader.active {
        display: flex;
    }
    .autocomplete-dropdown {
        position: absolute;
        background: white;
        border: 1px solid #ddd;
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        width: 100%;
        margin-top: 2px;
    }
    .autocomplete-dropdown.active {
        display: block;
    }
    .autocomplete-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
    }
    .autocomplete-item:hover {
        background-color: #f8f9fa;
    }
    .autocomplete-item:last-child {
        border-bottom: none;
    }
    .address-preview {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 12px 15px;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }
    .address-preview strong {
        color: #495057;
    }
    .address-preview .text-muted {
        font-size: 0.85rem;
    }
    .map-disabled-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(2px);
        z-index: 1001;
        border-radius: 0.375rem;
        text-align: center;
        padding: 1.5rem;
        color: #495057;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Nueva Ubicación</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('auth.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('locations.index') }}">Ubicaciones</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nueva</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        @if(empty($googleMapsKey))
            <div class="alert alert-warning">
                Debes configurar la clave de Google Maps en la sección de contacto antes de crear ubicaciones.
            </div>
        @endif

        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('locations.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre *</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="order" class="form-label">Orden</label>
                                <input type="number"
                                       class="form-control @error('order') is-invalid @enderror"
                                       id="order"
                                       name="order"
                                       value="{{ old('order', 0) }}"
                                       min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label d-block">Estado</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_published"
                                           name="is_published"
                                           value="1"
                                           {{ old('is_published', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Publicado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección *</label>
                            <div class="position-relative">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control @error('address') is-invalid @enderror"
                                           id="address"
                                           name="address"
                                           value="{{ old('address') }}"
                                           placeholder="Ej: Calle 123, Colonia Nombre, C.P. 00000, Estado, País"
                                           autocomplete="off"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary" id="searchAddress" title="Buscar en el mapa">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                                <div class="autocomplete-dropdown" id="autocompleteDropdown"></div>
                            </div>
                            <small class="text-muted">
                                <i class="mdi mdi-information-outline"></i>
                                Escribe la dirección completa (calle, número exterior, colonia, código postal, estado, país) y selecciona la mejor coincidencia.
                            </small>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ubicación en el mapa *</label>
                            <p class="text-muted small">Haz clic en el mapa para seleccionar la ubicación o escribe la dirección arriba</p>

                            <div class="address-preview" id="addressPreview" style="display: none;">
                                <div class="text-muted mb-1">Dirección que se guardará:</div>
                                <strong id="formattedAddress"></strong>
                            </div>

                            <div style="position: relative;">
                                <div id="map"></div>
                                <div class="map-loader" id="mapLoader">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </div>
                                @if(empty($googleMapsKey))
                                    <div class="map-disabled-overlay">
                                        Configura tu Google Maps API Key para habilitar el mapa interactivo.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitud *</label>
                                <input type="text"
                                       class="form-control @error('latitude') is-invalid @enderror"
                                       id="latitude"
                                       name="latitude"
                                       value="{{ old('latitude') }}"
                                       readonly
                                       required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitud *</label>
                                <input type="text"
                                       class="form-control @error('longitude') is-invalid @enderror"
                                       id="longitude"
                                       name="longitude"
                                       value="{{ old('longitude') }}"
                                       readonly
                                       required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('locations.index') }}" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-gradient-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(!empty($googleMapsKey))
    <script>
        const GOOGLE_MAPS_API_KEY = @json($googleMapsKey);
        const DEFAULT_LAT = 19.4326;
        const DEFAULT_LNG = -99.1332;
        const MAP_ZOOM = 14;

        const mapLoader = document.getElementById('mapLoader');
        const addressInput = document.getElementById('address');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const autocompleteDropdown = document.getElementById('autocompleteDropdown');
        const addressPreview = document.getElementById('addressPreview');
        const formattedAddressEl = document.getElementById('formattedAddress');
        const searchButton = document.getElementById('searchAddress');

        let map;
        let marker;
        let placesService;
        let geocoder;
        let autocompleteService;
        let searchTimeout = null;

        function showLoader() {
            mapLoader.classList.add('active');
        }

        function hideLoader() {
            mapLoader.classList.remove('active');
        }

        function updateAddressPreview(text) {
            if (!text) {
                addressPreview.style.display = 'none';
                formattedAddressEl.textContent = '';
                return;
            }
            formattedAddressEl.textContent = text;
            addressPreview.style.display = 'block';
        }

        function setMarkerPosition(position) {
            if (!marker) {
                marker = new google.maps.Marker({
                    map,
                    draggable: true,
                });

                marker.addListener('dragend', handleMarkerDragEnd);
            }
            marker.setPosition(position);
            map.panTo(position);
        }

        function handleMarkerDragEnd() {
            const position = marker.getPosition();
            if (!position) {
                return;
            }
            const lat = position.lat();
            const lng = position.lng();
            latitudeInput.value = lat.toFixed(6);
            longitudeInput.value = lng.toFixed(6);
            reverseGeocode(lat, lng);
        }

        function applyPlaceResult(result) {
            if (!result.geometry || !result.geometry.location) {
                return;
            }

            const location = result.geometry.location;
            const lat = location.lat();
            const lng = location.lng();
            latitudeInput.value = lat.toFixed(6);
            longitudeInput.value = lng.toFixed(6);
            setMarkerPosition(location);

            const formatted = result.formatted_address || result.description;
            if (formatted) {
                addressInput.value = formatted;
                updateAddressPreview(formatted);
            }
        }

        function reverseGeocode(lat, lng) {
            if (!geocoder) {
                return;
            }

            showLoader();
            geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                hideLoader();

                if (status !== google.maps.GeocoderStatus.OK || !results || !results.length) {
                    return;
                }

                const bestResult = results[0];
                const formatted = bestResult.formatted_address;

                if (formatted) {
                    addressInput.value = formatted;
                    updateAddressPreview(formatted);
                }
            });
        }

        function selectPrediction(prediction) {
            if (!prediction) {
                return;
            }
            autocompleteDropdown.classList.remove('active');
            showLoader();

            placesService.getDetails({ placeId: prediction.place_id }, (result, status) => {
                hideLoader();
                if (status !== google.maps.places.PlacesServiceStatus.OK || !result) {
                    return;
                }
                applyPlaceResult(result);
            });
        }

        function renderPredictions(predictions) {
            autocompleteDropdown.innerHTML = '';
            if (!predictions || !predictions.length) {
                autocompleteDropdown.classList.remove('active');
                return;
            }

            predictions.forEach(prediction => {
                const item = document.createElement('div');
                item.className = 'autocomplete-item';
                item.innerHTML = `
                    <div style="font-weight: 500;">${prediction.structured_formatting.main_text}</div>
                    <small style="color: #6c757d;">${prediction.structured_formatting.secondary_text || ''}</small>
                `;
                item.addEventListener('click', () => selectPrediction(prediction));
                autocompleteDropdown.appendChild(item);
            });

            autocompleteDropdown.classList.add('active');
        }

        function searchPredictions(query) {
            if (!autocompleteService) {
                return;
            }
            autocompleteService.getPlacePredictions({
                input: query,
                types: ['geocode'],
            }, (predictions, status) => {
                if (status !== google.maps.places.PlacesServiceStatus.OK || !predictions) {
                    renderPredictions([]);
                    return;
                }
                renderPredictions(predictions.slice(0, 5));
            });
        }

        function handleSearchClick() {
            const query = addressInput.value.trim();
            if (!query) {
                return;
            }

            showLoader();
            placesService.textSearch({ query }, (results, status) => {
                hideLoader();
                if (status !== google.maps.places.PlacesServiceStatus.OK || !results || !results.length) {
                    alert('No se pudo encontrar la dirección especificada. Intenta con otra búsqueda.');
                    return;
                }
                applyPlaceResult(results[0]);
            });
        }

        function initializeMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: DEFAULT_LAT, lng: DEFAULT_LNG },
                zoom: MAP_ZOOM,
            });

            placesService = new google.maps.places.PlacesService(map);
            geocoder = new google.maps.Geocoder();
            autocompleteService = new google.maps.places.AutocompleteService();

            map.addListener('click', (event) => {
                const lat = event.latLng.lat();
                const lng = event.latLng.lng();
                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);
                setMarkerPosition(event.latLng);
                reverseGeocode(lat, lng);
            });

            if (latitudeInput.value && longitudeInput.value) {
                const lat = parseFloat(latitudeInput.value);
                const lng = parseFloat(longitudeInput.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    setMarkerPosition(new google.maps.LatLng(lat, lng));
                }
            }
        }

        window.initAuthLocationMap = function() {
            initializeMap();

            addressInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                const query = addressInput.value.trim();
                if (query.length < 3) {
                    autocompleteDropdown.classList.remove('active');
                    return;
                }
                searchTimeout = setTimeout(() => searchPredictions(query), 300);
            });

            document.addEventListener('click', (event) => {
                if (!addressInput.contains(event.target) && !autocompleteDropdown.contains(event.target)) {
                    autocompleteDropdown.classList.remove('active');
                }
            });

            if (searchButton) {
                searchButton.addEventListener('click', handleSearchClick);
            }
        };
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&libraries=places&callback=initAuthLocationMap" async defer></script>
@endif
@endsection