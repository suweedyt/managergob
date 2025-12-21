@extends('layouts.auth')

@section('title', 'Configuración de Header y Footer')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/font-awesome/css/all.min.css') }}">
@endsection

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Configuración del sitio</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><span>Configuraciones</span></li>
                <li class="breadcrumb-item active" aria-current="page">Header y Footer</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Header &amp; Footer</h4>
                        <span class="text-muted">Administra el contenido visual del sitio</span>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('site-settings.store') }}" enctype="multipart/form-data" id="siteSettingsForm">
                        @csrf
                        <div class="accordion" id="siteSettingsAccordion">
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="headerConfigHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#headerConfig" aria-expanded="true" aria-controls="headerConfig">
                                        Configuración del Header
                                    </button>
                                </h2>
                                <div id="headerConfig" class="accordion-collapse collapse show" aria-labelledby="headerConfigHeading" data-bs-parent="#siteSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="header_height" class="form-label">Altura del header (px)</label>
                                                <input type="number" name="header_height" id="header_height" class="form-control" min="40" max="160" step="1" value="{{ old('header_height', optional($settings)->header_height ?? 80) }}" required>
                                                <small class="form-text text-muted">Recomendado entre 60px y 90px</small>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="header_background_color" class="form-label">Color de fondo (HEX)</label>
                                                <input type="color" name="header_background_color" id="header_background_color" class="form-control form-control-color" value="{{ old('header_background_color', optional($settings)->header_background_color ?? '#ffffff') }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="header_logo" class="form-label">Logotipo</label>
                                                <input type="file" name="header_logo" id="header_logo" class="form-control" accept="image/png,image/jpeg,image/svg+xml">
                                                <small class="form-text text-muted">Formato recomendado: PNG/SVG, dimensiones 220x70px, peso máx. 2MB</small>
                                                @if (optional($settings)->header_logo)
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/images/settings/' . $settings->header_logo) }}" alt="Logo actual" class="img-fluid rounded border" style="max-height: 80px;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="footerConfigHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#footerConfig" aria-expanded="false" aria-controls="footerConfig">
                                        Configuración del Footer
                                    </button>
                                </h2>
                                <div id="footerConfig" class="accordion-collapse collapse" aria-labelledby="footerConfigHeading" data-bs-parent="#siteSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="footer_background_color" class="form-label">Color de fondo</label>
                                                <input type="color" name="footer_background_color" id="footer_background_color" class="form-control form-control-color" value="{{ old('footer_background_color', optional($settings)->footer_background_color ?? '#101010') }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="footer_text_color" class="form-label">Color de texto</label>
                                                <input type="color" name="footer_text_color" id="footer_text_color" class="form-control form-control-color" value="{{ old('footer_text_color', optional($settings)->footer_text_color ?? '#ffffff') }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="footer_copy" class="form-label">Copy del footer</label>
                                                <input type="text" name="footer_copy" id="footer_copy" class="form-control" value="{{ old('footer_copy', optional($settings)->footer_copy) }}" placeholder="Ejemplo: © 2024 Ayuntamiento. Todos los derechos reservados.">
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <h5 class="mb-3">Columna 1: Logo de footer</h5>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <input type="file" name="footer_logo" id="footer_logo" class="form-control" accept="image/png,image/jpeg,image/svg+xml">
                                                    <small class="form-text text-muted">Formato recomendado: PNG/SVG, peso máximo 2MB</small>
                                                </div>
                                                @if(optional($settings)->footer_logo)
                                                    <div class="col-md-6">
                                                        <label class="form-label d-block">Vista previa</label>
                                                        <img src="{{ asset('storage/images/settings/' . $settings->footer_logo) }}" alt="Logo footer" class="img-fluid rounded border" style="max-height: 80px;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <h5 class="mb-3">Columna 2: Dirección y contacto</h5>
                                            <textarea name="footer_contact" id="footer_contact" rows="4" class="form-control" placeholder="Dirección completa, teléfonos, email, horario, etc.">{{ old('footer_contact', optional($settings)->footer_contact) }}</textarea>
                                        </div>

                                        <div class="mt-4" style="max-width: 70%; width: 70%;">
                                            <label class="form-label">Columna 3: Mapa del footer (buscar por dirección)</label>
                                            <input id="footer_map_address" type="text" class="form-control" placeholder="Escribe una dirección..." value="">
                                            <input id="footer_map_iframe" name="footer_map_iframe" type="hidden" value="{{ old('footer_map_iframe', $settings->footer_map_iframe ?? '') }}">
                                            <div id="footer_map_preview" class="mt-2">
                                                @if(!empty($settings->footer_map_iframe))
                                                    <img src="{{ $settings->footer_map_iframe }}" class="img-fluid rounded" alt="Preview mapa">
                                                @endif
                                            </div>
                                            <div id="footer_map_error" class="text-danger small mt-2" style="display:none;"></div>
                                        </div>

                                        <div class="mt-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="mb-0">Columna 4: Enlaces de interés</h5>
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="addLinkButton">Agregar enlace</button>
                                            </div>
                                            <p class="text-muted mb-3">Agrega enlaces de interés (texto y URL).</p>
                                            <div id="linksList" class="list-group">
                                                @php $links = old('footer_links', optional($settings)->footer_links ?? []); @endphp
                                                @forelse ($links as $index => $link)
                                                    <div class="list-group-item border rounded mb-2" data-index="{{ $index }}">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <strong>Enlace #{{ $loop->iteration }}</strong>
                                                            <button type="button" class="btn btn-sm btn-link text-danger remove-link">Eliminar</button>
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Texto</label>
                                                                <input type="text" name="footer_links[{{ $index }}][name]" class="form-control" value="{{ $link['name'] ?? '' }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">URL</label>
                                                                <input type="url" name="footer_links[{{ $index }}][url]" class="form-control" value="{{ $link['url'] ?? '' }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-muted mb-0" id="noLinksMessage">No hay enlaces configurados.</p>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="mb-0">Columna 5: Redes sociales</h5>
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="addSocialButton">Agregar red</button>
                                            </div>
                                            <p class="text-muted mb-3">Define las redes sociales que se mostrarán en la cuarta columna. Proporciona la URL del icono que se usará en el sitio.</p>
                                            <div id="socialList" class="list-group">
                                                @php
                                                    $socials = old('footer_socials', optional($settings)->footer_socials ?? []);
                                                @endphp
                                                @forelse ($socials as $index => $social)
                                                    <div class="list-group-item border rounded mb-2" data-index="{{ $index }}">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <strong>Red social #{{ $loop->iteration }}</strong>
                                                            <button type="button" class="btn btn-sm btn-link text-danger remove-social">Eliminar</button>
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Nombre</label>
                                                                <input type="text" name="footer_socials[{{ $index }}][name]" class="form-control @error('footer_socials.'.$index.'.name') is-invalid @enderror" value="{{ $social['name'] ?? '' }}" required>
                                                                @error('footer_socials.'.$index.'.name')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">URL</label>
                                                                <input type="url" name="footer_socials[{{ $index }}][url]" class="form-control @error('footer_socials.'.$index.'.url') is-invalid @enderror" value="{{ $social['url'] ?? '' }}" required>
                                                                @error('footer_socials.'.$index.'.url')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Icono (URL)</label>
                                                                <input type="url" name="footer_socials[{{ $index }}][icon_url]" class="form-control @error('footer_socials.'.$index.'.icon_url') is-invalid @enderror" value="{{ $social['icon_url'] ?? '' }}" placeholder="https://..." required>
                                                                @error('footer_socials.'.$index.'.icon_url')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-muted mb-0" id="noSocialsMessage">No hay redes sociales configuradas.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('auth.dashboard') }}" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-gradient-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        (function () {
            const socialList = document.getElementById('socialList');
            const addButton = document.getElementById('addSocialButton');
            const emptyMessageId = 'noSocialsMessage';

            function updateEmptyMessage() {
                if (!socialList) {
                    return;
                }

                const message = document.getElementById(emptyMessageId);
                const hasItems = socialList.querySelector('.list-group-item');

                if (!hasItems && !message) {
                    const newMessage = document.createElement('p');
                    newMessage.id = emptyMessageId;
                    newMessage.className = 'text-muted mb-0';
                    newMessage.textContent = 'No hay redes sociales configuradas.';
                    socialList.appendChild(newMessage);
                } else if (hasItems && message) {
                    message.remove();
                }
            }

            function attachValidationHandlers(elements) {
                elements.forEach(element => {
                    if (!element) {
                        return;
                    }
                    element.addEventListener('invalid', () => element.classList.add('is-invalid'));
                    element.addEventListener('input', () => element.classList.remove('is-invalid'));
                });
            }

            function buildSocialItem(index, values = {}) {
                const item = document.createElement('div');
                item.className = 'list-group-item border rounded mb-2';
                item.dataset.index = index;

                const header = document.createElement('div');
                header.className = 'd-flex justify-content-between align-items-center mb-2';

                const title = document.createElement('strong');
                title.textContent = `Red social #${index + 1}`;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-link text-danger remove-social';
                removeBtn.textContent = 'Eliminar';

                header.append(title, removeBtn);

                const row = document.createElement('div');
                row.className = 'row g-3';

                const nameCol = document.createElement('div');
                nameCol.className = 'col-md-4';
                const nameLabel = document.createElement('label');
                nameLabel.className = 'form-label';
                nameLabel.textContent = 'Nombre';
                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.name = `footer_socials[${index}][name]`;
                nameInput.className = 'form-control';
                nameInput.required = true;
                nameInput.value = values.name ?? '';
                nameCol.append(nameLabel, nameInput);

                const urlCol = document.createElement('div');
                urlCol.className = 'col-md-4';
                const urlLabel = document.createElement('label');
                urlLabel.className = 'form-label';
                urlLabel.textContent = 'URL';
                const urlInput = document.createElement('input');
                urlInput.type = 'url';
                urlInput.name = `footer_socials[${index}][url]`;
                urlInput.className = 'form-control';
                urlInput.required = true;
                urlInput.value = values.url ?? '';
                urlCol.append(urlLabel, urlInput);

                const iconCol = document.createElement('div');
                iconCol.className = 'col-md-4';
                const iconLabel = document.createElement('label');
                iconLabel.className = 'form-label';
                iconLabel.textContent = 'Icono (URL)';
                const iconInput = document.createElement('input');
                iconInput.type = 'url';
                iconInput.name = `footer_socials[${index}][icon_url]`;
                iconInput.className = 'form-control';
                iconInput.placeholder = 'https://...';
                iconInput.required = true;
                iconInput.value = values.icon_url ?? '';
                iconCol.append(iconLabel, iconInput);

                row.append(nameCol, urlCol, iconCol);
                item.append(header, row);

                attachValidationHandlers([nameInput, urlInput, iconInput]);

                return item;
            }

            function buildLinkItem(index, values = {}) {
                const item = document.createElement('div');
                item.className = 'list-group-item border rounded mb-2';
                item.dataset.index = index;

                const header = document.createElement('div');
                header.className = 'd-flex justify-content-between align-items-center mb-2';

                const title = document.createElement('strong');
                title.textContent = `Enlace #${index + 1}`;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-link text-danger remove-link';
                removeBtn.textContent = 'Eliminar';

                header.append(title, removeBtn);

                const row = document.createElement('div');
                row.className = 'row g-3';

                const nameCol = document.createElement('div');
                nameCol.className = 'col-md-6';
                const nameLabel = document.createElement('label');
                nameLabel.className = 'form-label';
                nameLabel.textContent = 'Texto';
                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.name = `footer_links[${index}][name]`;
                nameInput.className = 'form-control';
                nameInput.required = true;
                nameInput.value = values.name ?? '';
                nameCol.append(nameLabel, nameInput);

                const urlCol = document.createElement('div');
                urlCol.className = 'col-md-6';
                const urlLabel = document.createElement('label');
                urlLabel.className = 'form-label';
                urlLabel.textContent = 'URL';
                const urlInput = document.createElement('input');
                urlInput.type = 'url';
                urlInput.name = `footer_links[${index}][url]`;
                urlInput.className = 'form-control';
                urlInput.required = true;
                urlInput.value = values.url ?? '';
                urlCol.append(urlLabel, urlInput);

                row.append(nameCol, urlCol);
                item.append(header, row);

                attachValidationHandlers([nameInput, urlInput]);

                return item;
            }

            function reindexItems() {
                const items = socialList?.querySelectorAll('.list-group-item') ?? [];
                items.forEach((item, idx) => {
                    item.dataset.index = idx;
                    const title = item.querySelector('strong');
                    if (title) {
                        title.textContent = `Red social #${idx + 1}`;
                    }

                    const fields = item.querySelectorAll('[name]');
                    fields.forEach(field => {
                        const name = field.getAttribute('name');
                        if (!name) {
                            return;
                        }
                        field.setAttribute('name', name.replace(/footer_socials\[\d+\]/, `footer_socials[${idx}]`));
                    });
                });
            }

            function extractValues(element) {
                const name = element.querySelector('input[name$="[name]"]')?.value ?? '';
                const url = element.querySelector('input[name$="[url]"]')?.value ?? '';
                const iconUrl = element.querySelector('input[name$="[icon_url]"]')?.value ?? '';

                return {
                    name,
                    url,
                    icon_url: iconUrl,
                };
            }

            if (socialList) {
                const existingItems = Array.from(socialList.querySelectorAll('.list-group-item'));
                existingItems.forEach((item, idx) => {
                    item.dataset.index = idx;
                    const nameInput = item.querySelector('input[name$="[name]"]');
                    const urlInput = item.querySelector('input[name$="[url]"]');
                    const iconInput = item.querySelector('input[name$="[icon_url]"]');
                    attachValidationHandlers([nameInput, urlInput, iconInput].filter(Boolean));
                });
            }

            addButton?.addEventListener('click', () => {
                if (!socialList) {
                    return;
                }

                const newIndex = socialList.querySelectorAll('.list-group-item').length;
                const newItem = buildSocialItem(newIndex);
                socialList.appendChild(newItem);
                updateEmptyMessage();
            });

            socialList?.addEventListener('click', event => {
                const removeButton = event.target.closest('.remove-social');
                if (removeButton) {
                    const item = removeButton.closest('.list-group-item');
                    if (item) {
                        item.remove();
                        if (typeof reindexItems === 'function') {
                            reindexItems();
                        }
                        updateEmptyMessage();
                    }
                }
            });

            updateEmptyMessage();
        })();
    </script>
@endsection

@push('scripts')
    {{-- Cargar Places API si existe la clave en settings de contacto --}}
    @if(!empty(optional($contactSettings)->google_maps_api_key))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $contactSettings->google_maps_api_key }}&libraries=places" async defer></script>
    @endif

    <script>
        (function () {
            const apiKey = '{{ $contactSettings->google_maps_api_key ?? '' }}';
            const addressInput = document.getElementById('footer_map_address');
            const iframeInput = document.getElementById('footer_map_iframe');
            const preview = document.getElementById('footer_map_preview');
            const errorEl = document.getElementById('footer_map_error');

            function showError(msg) {
                if (!errorEl) return;
                errorEl.textContent = msg;
                errorEl.style.display = 'block';
                addressInput?.classList.add('is-invalid');
            }
            function clearError() {
                if (!errorEl) return;
                errorEl.textContent = '';
                errorEl.style.display = 'none';
                addressInput?.classList.remove('is-invalid');
            }

            if (!apiKey) {
                if (addressInput) {
                    addressInput.addEventListener('input', function () {
                        if (this.value.trim().length === 0) {
                            clearError();
                        } else {
                            showError('La clave de Google Maps no está configurada en Contact Settings. No se puede autocompletar la dirección.');
                        }
                    });
                }
                return;
            }

            function initAutocompleteIfReady() {
                if (!addressInput) return;
                if (window.google && google.maps && google.maps.places) {
                    clearError();
                    const ac = new google.maps.places.Autocomplete(addressInput, { types: ['geocode','establishment'] });
                    ac.addListener('place_changed', () => {
                        const place = ac.getPlace();
                        if (!place || !place.geometry) {
                            showError('No se obtuvo información de ubicación para la selección.');
                            return;
                        }
                        clearError();
                        const lat = place.geometry.location.lat();
                        const lng = place.geometry.location.lng();
                        let mapUrl = '';
                        if (apiKey) {
                            mapUrl = `https://maps.googleapis.com/maps/api/staticmap?center=${lat},${lng}&zoom=18&size=640x360&scale=2&markers=color:red|${lat},${lng}&key=${apiKey}`;
                        } else {
                            mapUrl = `https://staticmap.openstreetmap.de/staticmap.php?center=${lat},${lng}&zoom=18&size=640x360&markers=${lat},${lng},red-pushpin`;
                        }
                        iframeInput.value = mapUrl;
                        preview.innerHTML = `<img src="${mapUrl}" class="img-fluid rounded" alt="Preview mapa">`;
                    });
                } else {
                    showError('La librería de Google Maps no se pudo cargar. Verifica la clave y la conectividad.');
                }
            }

            const readyCheck = setInterval(() => {
                if (window.google && google.maps && google.maps.places) {
                    clearInterval(readyCheck);
                    initAutocompleteIfReady();
                }
            }, 300);

            setTimeout(() => {
                if (!(window.google && google.maps && google.maps.places)) {
                    showError('La librería de Google Maps tardó en cargar o está bloqueada. Comprueba la API Key en Contact Settings.');
                    clearInterval(readyCheck);
                }
            }, 5000);
        })();
    </script>
@endpush

@push('scripts')
    <script>
        (function () {
            /* --- helpers --- */
            function attachValidationHandlers(elements) {
                elements.forEach(element => {
                    if (!element) return;
                    element.addEventListener('invalid', () => element.classList.add('is-invalid'));
                    element.addEventListener('input', () => element.classList.remove('is-invalid'));
                });
            }

            /* --- Socials --- */
            const socialList = document.getElementById('socialList');
            const addSocialBtn = document.getElementById('addSocialButton');
            const noSocialsMsgId = 'noSocialsMessage';

            function updateSocialEmptyMessage() {
                if (!socialList) return;
                const message = document.getElementById(noSocialsMsgId);
                const hasItems = socialList.querySelector('.list-group-item');
                if (!hasItems && !message) {
                    const p = document.createElement('p');
                    p.id = noSocialsMsgId;
                    p.className = 'text-muted mb-0';
                    p.textContent = 'No hay redes sociales configuradas.';
                    socialList.appendChild(p);
                } else if (hasItems && message) {
                    message.remove();
                }
            }

            function buildSocialItem(index, values = {}) {
                const item = document.createElement('div');
                item.className = 'list-group-item border rounded mb-2';
                item.dataset.index = index;

                const header = document.createElement('div');
                header.className = 'd-flex justify-content-between align-items-center mb-2';

                const title = document.createElement('strong');
                title.textContent = `Red social #${index + 1}`;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-link text-danger remove-social';
                removeBtn.textContent = 'Eliminar';

                header.append(title, removeBtn);

                const row = document.createElement('div');
                row.className = 'row g-3';

                const nameCol = document.createElement('div');
                nameCol.className = 'col-md-4';
                const nameLabel = document.createElement('label');
                nameLabel.className = 'form-label';
                nameLabel.textContent = 'Nombre';
                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.name = `footer_socials[${index}][name]`;
                nameInput.className = 'form-control';
                nameInput.required = true;
                nameInput.value = values.name ?? '';
                nameCol.append(nameLabel, nameInput);

                const urlCol = document.createElement('div');
                urlCol.className = 'col-md-4';
                const urlLabel = document.createElement('label');
                urlLabel.className = 'form-label';
                urlLabel.textContent = 'URL';
                const urlInput = document.createElement('input');
                urlInput.type = 'url';
                urlInput.name = `footer_socials[${index}][url]`;
                urlInput.className = 'form-control';
                urlInput.required = true;
                urlInput.value = values.url ?? '';
                urlCol.append(urlLabel, urlInput);

                const iconCol = document.createElement('div');
                iconCol.className = 'col-md-4';
                const iconLabel = document.createElement('label');
                iconLabel.className = 'form-label';
                iconLabel.textContent = 'Icono (URL)';
                const iconInput = document.createElement('input');
                iconInput.type = 'url';
                iconInput.name = `footer_socials[${index}][icon_url]`;
                iconInput.className = 'form-control';
                iconInput.placeholder = 'https://...';
                iconInput.required = true;
                iconInput.value = values.icon_url ?? '';
                iconCol.append(iconLabel, iconInput);

                row.append(nameCol, urlCol, iconCol);
                item.append(header, row);

                attachValidationHandlers([nameInput, urlInput, iconInput]);

                return item;
            }

            function reindexSocials() {
                const items = socialList?.querySelectorAll('.list-group-item') ?? [];
                items.forEach((item, idx) => {
                    item.dataset.index = idx;
                    const title = item.querySelector('strong');
                    if (title) title.textContent = `Red social #${idx + 1}`;
                    item.querySelectorAll('[name]').forEach(field => {
                        const name = field.getAttribute('name');
                        if (!name) return;
                        field.setAttribute('name', name.replace(/footer_socials\[\d+\]/, `footer_socials[${idx}]`));
                    });
                });
            }

            if (socialList) {
                Array.from(socialList.querySelectorAll('.list-group-item')).forEach((item, idx) => {
                    item.dataset.index = idx;
                    attachValidationHandlers(Array.from(item.querySelectorAll('input')));
                });

                addSocialBtn?.addEventListener('click', () => {
                    const newIndex = socialList.querySelectorAll('.list-group-item').length;
                    socialList.appendChild(buildSocialItem(newIndex));
                    updateSocialEmptyMessage();
                });

                socialList.addEventListener('click', (e) => {
                    const rem = e.target.closest('.remove-social');
                    if (!rem) return;
                    const item = rem.closest('.list-group-item');
                    if (item) {
                        item.remove();
                        reindexSocials();
                        updateSocialEmptyMessage();
                    }
                });

                updateSocialEmptyMessage();
            }

            /* --- Links (nuevo) --- */
            const linksList = document.getElementById('linksList');
            const addLinkBtn = document.getElementById('addLinkButton');
            const noLinksMsgId = 'noLinksMessage';

            function updateLinksEmptyMessage() {
                if (!linksList) return;
                const message = document.getElementById(noLinksMsgId);
                const hasItems = linksList.querySelector('.list-group-item');
                if (!hasItems && !message) {
                    const p = document.createElement('p');
                    p.id = noLinksMsgId;
                    p.className = 'text-muted mb-0';
                    p.textContent = 'No hay enlaces configurados.';
                    linksList.appendChild(p);
                } else if (hasItems && message) {
                    message.remove();
                }
            }

            function buildLinkItem(index, values = {}) {
                const item = document.createElement('div');
                item.className = 'list-group-item border rounded mb-2';
                item.dataset.index = index;

                const header = document.createElement('div');
                header.className = 'd-flex justify-content-between align-items-center mb-2';

                const title = document.createElement('strong');
                title.textContent = `Enlace #${index + 1}`;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-link text-danger remove-link';
                removeBtn.textContent = 'Eliminar';

                header.append(title, removeBtn);

                const row = document.createElement('div');
                row.className = 'row g-3';

                const nameCol = document.createElement('div');
                nameCol.className = 'col-md-6';
                const nameLabel = document.createElement('label');
                nameLabel.className = 'form-label';
                nameLabel.textContent = 'Texto';
                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.name = `footer_links[${index}][name]`;
                nameInput.className = 'form-control';
                nameInput.required = true;
                nameInput.value = values.name ?? '';
                nameCol.append(nameLabel, nameInput);

                const urlCol = document.createElement('div');
                urlCol.className = 'col-md-6';
                const urlLabel = document.createElement('label');
                urlLabel.className = 'form-label';
                urlLabel.textContent = 'URL';
                const urlInput = document.createElement('input');
                urlInput.type = 'url';
                urlInput.name = `footer_links[${index}][url]`;
                urlInput.className = 'form-control';
                urlInput.required = true;
                urlInput.value = values.url ?? '';
                urlCol.append(urlLabel, urlInput);

                row.append(nameCol, urlCol);
                item.append(header, row);

                attachValidationHandlers([nameInput, urlInput]);

                return item;
            }

            function reindexLinks() {
                const items = linksList?.querySelectorAll('.list-group-item') ?? [];
                items.forEach((item, idx) => {
                    item.dataset.index = idx;
                    const title = item.querySelector('strong');
                    if (title) title.textContent = `Enlace #${idx + 1}`;
                    item.querySelectorAll('[name]').forEach(field => {
                        const name = field.getAttribute('name');
                        if (!name) return;
                        field.setAttribute('name', name.replace(/footer_links\[\d+\]/, `footer_links[${idx}]`));
                    });
                });
            }

            if (linksList) {
                // Attach handlers to existing items
                Array.from(linksList.querySelectorAll('.list-group-item')).forEach((item, idx) => {
                    item.dataset.index = idx;
                    attachValidationHandlers(Array.from(item.querySelectorAll('input')));
                });

                addLinkBtn?.addEventListener('click', () => {
                    const newIndex = linksList.querySelectorAll('.list-group-item').length;
                    // if "no links" message present, remove it
                    const noMsg = document.getElementById(noLinksMsgId);
                    if (noMsg) noMsg.remove();
                    linksList.appendChild(buildLinkItem(newIndex));
                    updateLinksEmptyMessage();
                });

                linksList.addEventListener('click', (e) => {
                    const rem = e.target.closest('.remove-link');
                    if (!rem) return;
                    const item = rem.closest('.list-group-item');
                    if (item) {
                        item.remove();
                        reindexLinks();
                        updateLinksEmptyMessage();
                    }
                });

                updateLinksEmptyMessage();
            }
        })();
    </script>
@endpush