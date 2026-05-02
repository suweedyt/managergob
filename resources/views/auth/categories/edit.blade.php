@extends('layouts.auth')

@section('title', 'Editar Categoría')

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Editar Categoría </h3>
        </div>

        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="{{ $type }}">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label for="position">Posición</label>
                                <input type="number" min="0" name="position" id="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position', $category->position) }}">
                                @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Tipo</label>
                                <input type="text" class="form-control" value="{{ $type === 'tramite' ? 'Trámites' : 'Noticias' }}" disabled>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('categories.index', ['type' => $type]) }}" class="btn btn-light">Cancelar</a>
                                <button class="btn btn-gradient-primary" type="submit">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
