@extends('layouts.auth')

@section('title', 'Configuración - Trámites')

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Configuración - Trámites</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><span>Configuraciones</span></li>
                <li class="breadcrumb-item active" aria-current="page">Trámites</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Configuración de la página de Trámites</h4>
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

                    <form method="POST" action="{{ route('tramitessettings.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="accordion" id="tramitesSettingsAccordion">
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="tramitesGeneralHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#tramitesGeneral" aria-expanded="true" aria-controls="tramitesGeneral">
                                        General
                                    </button>
                                </h2>
                                <div id="tramitesGeneral" class="accordion-collapse collapse show" aria-labelledby="tramitesGeneralHeading" data-bs-parent="#tramitesSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label for="title" class="form-label">Título de la sección</label>
                                                <input id="title" name="title" type="text" class="form-control" value="{{ old('title', optional($tramitessettings)->title) }}">
                                                @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-8">
                                                <label for="subtitle" class="form-label">Subtítulo</label>
                                                <input id="subtitle" name="subtitle" type="text" class="form-control" value="{{ old('subtitle', optional($tramitessettings)->subtitle) }}">
                                                @error('subtitle') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="tramitesLogoHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tramitesLogo" aria-expanded="false" aria-controls="tramitesLogo">
                                        Logo
                                    </button>
                                </h2>
                                <div id="tramitesLogo" class="accordion-collapse collapse" aria-labelledby="tramitesLogoHeading" data-bs-parent="#tramitesSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-md-4">
                                                <label class="form-label">Logo actual</label>
                                                <div>
                                                    @if(optional($tramitessettings)->logo_image)
                                                        <img src="{{ asset(optional($tramitessettings)->logo_image) }}" alt="Logo trámites" class="img-fluid" style="max-height:80px; object-fit:contain;">
                                                    @else
                                                        <div class="text-muted small">No se ha configurado logo.</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <label for="logo_image" class="form-label">Subir nuevo logo</label>
                                                <input id="logo_image" name="logo_image" type="file" accept="image/*" class="form-control">
                                                @error('logo_image') <div class="text-danger small">{{ $message }}</div> @enderror
                                                <div class="form-text">Formatos permitidos: jpg, jpeg, png, webp. Máx 5MB.</div>
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
