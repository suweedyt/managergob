@extends('layouts.auth')

@section('title', 'Editar Ubicación')

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
        <h3 class="page-title">Editar Ubicación</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('auth.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('locations.index') }}">Ubicaciones</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        @if(empty($googleMapsKey))
            <div class="alert alert-warning">
                Debes configurar la clave de Google Maps en la secci n de contacto antes de modificar ubicaciones.
            </div>
        @endif

        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('locations.update', $location->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre *</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $location->name) }}"
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
                                       value="{{ old('order', $location->order) }}"
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
                                           {{ old('is_published', $location->is_published) ? 'checked' : '' }}>
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
                                           value="{{ old('address', $location->address) }}"
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
                            <p class="text-muted small">Haz clic en el mapa para cambiar la ubicación o escribe la dirección arriba</p>

                            <div class="address-preview" id="addressPreview">
                                <div class="text-muted mb-1">Dirección que se guardará:</div>
                                <strong id="formattedAddress">{{ $location->address }}</strong>
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
                                       value="{{ old('latitude', $location->latitude) }}"
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
                                       value="{{ old('longitude', $location->longitude) }}"
                                       readonly
                                       required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('locations.index') }}" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-gradient-primary">Actualizar</button>
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
        const DEFAULT_LAT = {{ $location->latitude ?? 19.4326 }};
        const DEFAULT_LNG = {{ $location->longitude ?? -99.1332 }};
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

            autocompleteDropdown.classList.remove('show');
            addressInput.value = prediction.description;
            updateAddressPreview(prediction.description);

            if (!placesService) {
                placesService = new google.maps.places.PlacesService(map);
            }

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
                autocompleteDropdown.classList.remove('show');
                return;
            }

            predictions.forEach((prediction) => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.innerHTML = `
                    <div class="d-flex flex-column">
                        <strong>${prediction.structured_formatting.main_text}</strong>
                        <small class="text-muted">${prediction.structured_formatting.secondary_text || ''}</small>
                    </div>
                `;
                item.addEventListener('click', () => selectPrediction(prediction));
                autocompleteDropdown.appendChild(item);
            });

            autocompleteDropdown.classList.add('show');
        }

        function searchPredictions(query) {
            if (!autocompleteService) {
                autocompleteService = new google.maps.places.AutocompleteService();
            }

            if (!query) {
                autocompleteDropdown.classList.remove('show');
                return;
            }

            autocompleteService.getPlacePredictions({ input: query }, (predictions) => {
                renderPredictions(predictions);
            });
        }

        function debounceSearch(query) {
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            searchTimeout = setTimeout(() => searchPredictions(query), 250);
        }

        function initializeMap() {
            const mapEl = document.getElementById('map');
            if (!mapEl) {
                return;
            }

            const initialPosition = {
                lat: parseFloat(latitudeInput.value) || DEFAULT_LAT,
                lng: parseFloat(longitudeInput.value) || DEFAULT_LNG,
            };

            map = new google.maps.Map(mapEl, {
                center: initialPosition,
                zoom: MAP_ZOOM,
                mapTypeControl: false,
                streetViewControl: false,
            });

            geocoder = new google.maps.Geocoder();

            setMarkerPosition(initialPosition);
        }

        function loadGoogleMapsScript(callback) {
            if (window.google && window.google.maps) {
                callback();
                return;
            }

            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=${GOOGLE_MAPS_API_KEY}&libraries=places`;
            script.async = true;
            script.defer = true;
            script.onload = callback;
            document.head.appendChild(script);
        }

        addressInput.addEventListener('input', (event) => {
            debounceSearch(event.target.value);
        });

        addressInput.addEventListener('focus', () => {
            if (autocompleteDropdown.children.length) {
                autocompleteDropdown.classList.add('show');
            }
        });

        document.addEventListener('click', (event) => {
            if (!autocompleteDropdown.contains(event.target) && event.target !== addressInput) {
                autocompleteDropdown.classList.remove('show');
            }
        });

        if (searchButton) {
            searchButton.addEventListener('click', () => {
                const query = addressInput.value;
                if (query) {
                    searchPredictions(query);
                }
            });
        }

        loadGoogleMapsScript(() => {
            initializeMap();
            updateAddressPreview(addressInput.value);
        });
    </script>
@endif
@endsection