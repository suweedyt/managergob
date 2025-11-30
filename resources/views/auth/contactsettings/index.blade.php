@extends('layouts.auth')

@section('title', 'Configuración - Contacto')

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Configuración - Contacto</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><span>Configuraciones</span></li>
                <li class="breadcrumb-item active" aria-current="page">Contacto</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Configuración de la página de Contacto</h4>
                        <span class="text-muted">Define el título y subtítulo visibles en el frontend</span>
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

                    <form method="POST" action="{{ route('contactsettings.store') }}">
                        @csrf

                        <div class="accordion" id="contactSettingsAccordion">
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="contactGeneralHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#contactGeneral" aria-expanded="true" aria-controls="contactGeneral">
                                        General
                                    </button>
                                </h2>
                                <div id="contactGeneral" class="accordion-collapse collapse show" aria-labelledby="contactGeneralHeading" data-bs-parent="#contactSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label for="title" class="form-label">Título de la sección</label>
                                                <input id="title" name="title" type="text" class="form-control" value="{{ old('title', optional($contactsettings)->title) }}">
                                                @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-8">
                                                <label for="subtitle" class="form-label">Subtítulo</label>
                                                <input id="subtitle" name="subtitle" type="text" class="form-control" value="{{ old('subtitle', optional($contactsettings)->subtitle) }}">
                                                @error('subtitle') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="contactMapHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contactMap" aria-expanded="false" aria-controls="contactMap">
                                        Mapas y API Keys
                                    </button>
                                </h2>
                                <div id="contactMap" class="accordion-collapse collapse" aria-labelledby="contactMapHeading" data-bs-parent="#contactSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label for="google_maps_api_key" class="form-label">Google Maps API Key</label>
                                                <input id="google_maps_api_key" name="google_maps_api_key" type="text" class="form-control" value="{{ old('google_maps_api_key', optional($contactsettings)->google_maps_api_key) }}">
                                                <small class="text-muted d-block mt-1">Utiliza la versión gratuita de Google Maps Platform. Asegúrate de habilitar Places, Maps JavaScript y Geocoding APIs.</small>
                                                @error('google_maps_api_key') <div class="text-danger small">{{ $message }}</div> @enderror
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
