@extends('layouts.auth')

@section('title', 'Configuración - Secciones')

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Configuración - Secciones</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><span>Configuraciones</span></li>
                <li class="breadcrumb-item active" aria-current="page">Secciones</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Configuración de la página de Secciones</h4>
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

                    <form method="POST" action="{{ route('sectionssettings.store') }}">
                        @csrf

                        <div class="accordion" id="sectionsSettingsAccordion">
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="sectionsGeneralHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#sectionsGeneral" aria-expanded="true" aria-controls="sectionsGeneral">
                                        General
                                    </button>
                                </h2>
                                <div id="sectionsGeneral" class="accordion-collapse collapse show" aria-labelledby="sectionsGeneralHeading" data-bs-parent="#sectionsSettingsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label for="title" class="form-label">Título de la sección</label>
                                                <input id="title" name="title" type="text" class="form-control" value="{{ old('title', optional($sectionssettings)->title) }}">
                                                @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-8">
                                                <label for="subtitle" class="form-label">Subtítulo</label>
                                                <input id="subtitle" name="subtitle" type="text" class="form-control" value="{{ old('subtitle', optional($sectionssettings)->subtitle) }}">
                                                @error('subtitle') <div class="text-danger small">{{ $message }}</div> @enderror
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
