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
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Noticias</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Categorías</li>
                </ol>
            </nav>
        </div>

        <div class="row mb-3">
            <div class="col">
                <a href="{{ route('categories.create') }}" class="btn btn-gradient-primary">Nueva Categoría</a>
                <a href="{{ route('posts.index') }}" class="btn btn-light">Volver a Noticias</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if(count($categories) > 0)
                            <ul class="list-group">
                                @foreach($categories as $category)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $category->name }}
                                        <div>
                                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
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
