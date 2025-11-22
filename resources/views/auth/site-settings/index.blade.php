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
                                                        <img src="{{ asset('images/settings/' . $settings->header_logo) }}" alt="Logo actual" class="img-fluid rounded border" style="max-height: 80px;">
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
                                            <div class="col-md-8">
                                                <label for="footer_copy" class="form-label">Copy del footer</label>
                                                <input type="text" name="footer_copy" id="footer_copy" class="form-control" value="{{ old('footer_copy', optional($settings)->footer_copy) }}" placeholder="Ejemplo: © 2024 Ayuntamiento. Todos los derechos reservados.">
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <h5 class="mb-3">Columna 1: Dirección y contacto</h5>
                                            <label for="footer_contact" class="form-label">Contenido</label>
                                            <textarea name="footer_contact" id="footer_contact" rows="4" class="form-control" placeholder="Dirección completa, teléfonos, email, horario, etc.">{{ old('footer_contact', optional($settings)->footer_contact) }}</textarea>
                                        </div>

                                        <div class="mt-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="mb-0">Columna 2: Redes sociales</h5>
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="addSocialButton">Agregar red</button>
                                            </div>
                                            <p class="text-muted mb-3">Define las redes sociales que se mostrarán en la segunda columna. Proporciona la URL del icono que se usará en el sitio.</p>
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