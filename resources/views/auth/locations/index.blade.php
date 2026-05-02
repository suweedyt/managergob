@extends('layouts.auth')

@section('title', 'Ubicaciones')

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Ubicaciones</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('auth.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ubicaciones</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Listado de Ubicaciones</h4>
                        <a href="{{ route('locations.create') }}" class="btn btn-gradient-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Nueva Ubicación
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Coordenadas</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locations as $location)
                                    <tr>
                                        <td>{{ $location->order }}</td>
                                        <td>{{ $location->name }}</td>
                                        <td>{{ Str::limit($location->address, 50) }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $location->latitude }}, {{ $location->longitude }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($location->is_published)
                                                <span class="mdi mdi-check"></span>
                                            @else
                                                <span class="mdi mdi-close"></span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('locations.edit', $location->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('locations.destroy', $location->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar esta ubicación?');"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-muted mb-0">No hay ubicaciones registradas</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection