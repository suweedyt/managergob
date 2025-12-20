@extends('layouts.auth')

@section('title', 'Configuración - Sección Destacada')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/summernote/summernote.min.css') }}">
@endsection

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Configuraciones</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><span>Configuraciones</span></li>
                <li class="breadcrumb-item active" aria-current="page">Sección destacada</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Sección destacada (página principal)</h4>
                        <span class="text-muted">Define el contenido del bloque destacado en la home</span>
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

                    <form method="POST" action="{{ route('featuresettings.store') }}" enctype="multipart/form-data">
                        @csrf

                        @php $linkTypeSelected = old('link_type', optional($settings)->link_type ?? 'url'); @endphp

                        <div class="accordion" id="featureSettingsAccordion">
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="featureGeneralHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#featureGeneral" aria-expanded="true" aria-controls="featureGeneral">
                                        Contenido
                                    </button>
                                </h2>
                                <div id="featureGeneral" class="accordion-collapse collapse show" aria-labelledby="featureGeneralHeading" data-bs-parent="#featureSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label for="title" class="form-label">Título</label>
                                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', optional($settings)->title) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="button_text" class="form-label">Texto del botón</label>
                                                <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text', optional($settings)->button_text) }}">
                                            </div>
                                            <div class="col-md-12">
                                                <label for="subtitle" class="form-label">Subtítulo</label>
                                                <textarea name="subtitle" id="subtitle" class="form-control" rows="3">{{ old('subtitle', optional($settings)->subtitle) }}</textarea>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="button_color" class="form-label">Color del botón</label>
                                                <input type="color" name="button_color" id="button_color" class="form-control form-control-color" value="{{ old('button_color', optional($settings)->button_color ?? '#FF7A00') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="featureActionHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#featureAction" aria-expanded="false" aria-controls="featureAction">
                                        Acción del botón
                                    </button>
                                </h2>
                                <div id="featureAction" class="accordion-collapse collapse" aria-labelledby="featureActionHeading" data-bs-parent="#featureSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Tipo de destino</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="link_type" id="link_type_url" value="url" {{ old('link_type', optional($settings)->link_type ?? 'url') === 'url' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="link_type_url">URL</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="link_type" id="link_type_landing" value="landing" {{ old('link_type', optional($settings)->link_type ?? 'url') === 'landing' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="link_type_landing">Landing</label>
                                                </div>
                                            </div>

                                            <div class="col-md-8 {{ $linkTypeSelected === 'landing' ? 'd-none' : '' }}" id="urlWrapper">
                                                <label for="button_url" class="form-label">URL del botón</label>
                                                <input type="url" name="button_url" id="button_url" class="form-control" placeholder="https://" value="{{ old('button_url', optional($settings)->button_url) }}">
                                            </div>

                                            <div class="col-12 {{ $linkTypeSelected === 'url' ? 'd-none' : '' }}" id="landingWrapper">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="slug" class="form-label">Slug amigable</label>
                                                        <input type="text" name="slug" id="slug" class="form-control" placeholder="mi-landing-destacado" value="{{ old('slug', optional($settings)->slug) }}">
                                                        <small class="text-muted">Solo minúsculas, números y guiones. Si se deja vacío, se genera del título.</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="landing_image" class="form-label">Imagen principal</label>
                                                        <input type="file" name="landing_image" id="landing_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                                                        @if(optional($settings)->landing_image)
                                                            <small class="d-block mt-1">Actual: <a href="{{ asset($settings->landing_image) }}" target="_blank">Ver imagen</a></small>
                                                        @endif
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="landing_content" class="form-label">Contenido de la landing</label>
                                                        <textarea name="landing_content" id="landing_content" class="form-control summernote" rows="8">{{ old('landing_content', optional($settings)->landing_content) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="featureBackgroundHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#featureBackground" aria-expanded="false" aria-controls="featureBackground">
                                        Imagen de fondo
                                    </button>
                                </h2>
                                <div id="featureBackground" class="accordion-collapse collapse" aria-labelledby="featureBackgroundHeading" data-bs-parent="#featureSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label for="background_image" class="form-label">Imagen</label>
                                                <input type="file" name="background_image" id="background_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                                                <small class="text-muted">Resolución recomendada 1600x800 px. Peso máximo 2 MB.</small>
                                            </div>
                                            @if(optional($settings)->background_image)
                                                <div class="col-md-6">
                                                    <label class="form-label d-block">Vista previa actual</label>
                                                    <div class="border rounded p-2 bg-light text-center">
                                                        <img src="{{ asset($settings->background_image) }}" alt="Fondo actual" class="img-fluid" style="max-height: 180px; object-fit: cover;">
                                                    </div>
                                                </div>
                                            @endif
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
    <script src="{{ asset('assets/website/plugins/summernote/summernote.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const linkTypeUrl = document.getElementById('link_type_url');
            const linkTypeLanding = document.getElementById('link_type_landing');
            const urlWrapper = document.getElementById('urlWrapper');
            const landingWrapper = document.getElementById('landingWrapper');
            const slugInput = document.getElementById('slug');
            const titleInput = document.getElementById('title');

            $('.summernote').summernote({
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            function toggleActionFields() {
                if (linkTypeUrl && linkTypeUrl.checked) {
                    urlWrapper?.classList.remove('d-none');
                    landingWrapper?.classList.add('d-none');
                } else {
                    urlWrapper?.classList.add('d-none');
                    landingWrapper?.classList.remove('d-none');
                }
            }

            function slugify(text) {
                return text.toString().toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            slugInput?.addEventListener('blur', function() {
                if (!this.value && titleInput?.value) {
                    this.value = slugify(titleInput.value);
                }
            });

            linkTypeUrl?.addEventListener('change', toggleActionFields);
            linkTypeLanding?.addEventListener('change', toggleActionFields);
            toggleActionFields();
        });
    </script>
@endsection
