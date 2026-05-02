@extends('layouts.auth')

@section('title', 'Administrar Trámites')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/datatables/jquery.dataTables.min.css') }}" />
@endsection

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Trámites </h3>
        </div>

        <div class="row mb-3">
            <div class="col container btn-addnew">
                <a href="{{ route('tramites.create') }}" class="btn btn-gradient-primary me-2">Nuevo Trámite</a>
                <a href="{{ route('categories.index') }}?type=tramite" class="btn btn-outline-secondary">Administrar Categorías</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if(count($tramites) > 0)
                            <div id="tramitesTableWrapper">
                                <table id="tramites-table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Logo</th>
                                            <th>Título</th>
                                            <th>Subtitulo</th>
                                            <th>Publicado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tramites as $tramite)
                                            <tr>
                                                <td style="width:80px;">
                                                    @if($tramite->logo_image)
                                                        <img src="{{ asset($tramite->logo_image) }}" alt="logo" style="max-width:60px;">
                                                    @elseif($tramite->logo_class)
                                                        <i class="{{ $tramite->logo_class }}" style="font-size:24px"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $tramite->title_full }}</td>
                                                <td>{{ $tramite->title_short }}</td>
                                                <td>
                                                    @if ($tramite->is_published)
                                                        <span class="mdi mdi-check"></span>
                                                    @else
                                                        <span class="mdi mdi-close"></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('tramites.edit', $tramite->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('tramites.destroy', $tramite->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Eliminar trámite?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">No hay trámites creados aún.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/website/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script>
        $(function() {
            $('#tramites-table').DataTable({
                language: { url: '{{ asset("assets/website/plugins/datatables/lang/es-ES.json") }}' }
            });
        });
    </script>
@endsection