@extends('layouts.auth')

@section('title', 'Configuración del administrador')

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Configuración del administrador</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><span>Configuraciones</span></li>
                <li class="breadcrumb-item active" aria-current="page">Logotipos del panel</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Logotipos del panel administrativo</h4>
                        <span class="text-muted">Actualiza los logotipos mostrados en la barra superior</span>
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

                    <form method="POST" action="{{ route('admin-settings.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h5 class="mb-3">Logo completo</h5>
                                    <p class="text-muted">Se muestra en la barra superior cuando el menú lateral está expandido.</p>
                                    <div class="mb-3">
                                        <label for="logo_full" class="form-label">Seleccionar imagen</label>
                                        <input type="file" name="logo_full" id="logo_full" class="form-control" accept="image/png,image/jpeg,image/svg+xml">
                                        <small class="form-text text-muted">Formato recomendado: PNG/SVG, dimensiones aproximadas 160x40px, hasta 2MB.</small>
                                    </div>
                                    @if(optional($settings)->logo_full)
                                        <div class="mt-3">
                                            <span class="text-muted d-block mb-2">Logo actual:</span>
                                            <img src="{{ asset('images/settings/' . $settings->logo_full) }}" alt="Logo completo" class="img-fluid border rounded" style="max-height: 80px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h5 class="mb-3">Logo mini</h5>
                                    <p class="text-muted">Se muestra en la barra superior cuando el menú está contraído.</p>
                                    <div class="mb-3">
                                        <label for="logo_mini" class="form-label">Seleccionar imagen</label>
                                        <input type="file" name="logo_mini" id="logo_mini" class="form-control" accept="image/png,image/jpeg,image/svg+xml">
                                        <small class="form-text text-muted">Formato recomendado: PNG/SVG, dimensiones aproximadas 40x40px, hasta 2MB.</small>
                                    </div>
                                    @if(optional($settings)->logo_mini)
                                        <div class="mt-3">
                                            <span class="text-muted d-block mb-2">Logo actual:</span>
                                            <img src="{{ asset('images/settings/' . $settings->logo_mini) }}" alt="Logo mini" class="img-fluid border rounded" style="max-height: 60px;">
                                        </div>
                                    @endif
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
