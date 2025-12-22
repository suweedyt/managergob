@extends('layouts.auth')

@section('title', 'Administrar Categorías')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/font-awesome/css/all.min.css') }}" />
@endsection

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Categorías </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('categories.index', ['type' => 'news']) }}">Categorías</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $type === 'tramite' ? 'Trámites' : 'Noticias' }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="header-subsection mb-5">
                            <h4 class="card-title">Crear Categorías {{ $type === 'tramite' ? 'Trámites' : 'Noticias' }}</h4>
                            <div>
                                <a href="{{ route('categories.create', ['type' => $type]) }}" class="btn btn-gradient-primary">Nueva Categoría</a>
                            </div>
                        </div>

                        @if(count($categories) > 0)
                            <ul class="list-group">
                                @foreach($categories as $category)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $category->name }}</strong>
                                            <span class="text-muted small">#{{ $category->position }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('categories.edit', ['category' => $category->id, 'type' => $type]) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Eliminar categoría?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-info">No hay categorías aún.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
