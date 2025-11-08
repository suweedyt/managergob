@extends('layouts.auth')

@section('title', 'Administrar Noticias')

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/font-awesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/datatables/jquery.dataTables.min.css') }}" />
@endsection

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Noticias </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Noticias</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Todas</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="container btn-addnew">
                <a href="{{ route('posts.create') }}" type="submit" class="btn btn-gradient-primary me-2">Nueva Noticia</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if (count($posts) > 0)
                            <div id="postsTableWrapper">
                                <table id="news-table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th> Imagen </th>
                                        <th> Titulo </th>
                                        <th> Descripcion </th>
                                        <th> Fecha </th>
                                        <th> Banner </th>
                                        <th> Slider home </th>
                                        <th> Status </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                        <tr>
                                            <td class="py-1">
                                                <img src="{{ $post->gallery->image }}" alt="image" />
                                            </td>
                                            <td> {{ $post->title }} </td>
                                            <td>
                                                {!! limitHtml($post->description, 10, '...') !!}
                                            </td>
                                            <td> {{ date('d M Y', strtotime($post->created_at)); }} </td>
                                            <td> {{ $post->is_slider ? 'Si' : 'No' }} </td>
                                            <td> {{ $post->is_news_slider ? 'Si' : 'No' }} </td>
                                            <td class="text-center check-status-column">
                                                @if ($post->is_published == 1)
                                                    <span class="mdi mdi-check"></span>
                                                @else
                                                    <span class="mdi mdi-close"></span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-secondary btn-preview" data-url="{{ route('posts.preview', $post->id) }}" title="Vista previa"><i class="fas fa-eye"></i></button>
                                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ route('posts.destroy', $post->id) }}"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </table>
                            </div>

                            <div id="postPreviewContainer" style="display: none;">
                                <!-- AJAX preview will be injected here -->
                            </div>
                        @else
                            <div class="alert alert-primary text-center" role="alert">
                                No hay noticias
                            </div>
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
        $(document).ready(function() {
            $('#news-table').DataTable();
        });
    </script>

    <script>
        $(function() {
            var table = $('#news-table').DataTable();
            var deleteUrl = null;
            var $rowToDelete = null;
            var $previewModal = null;
            var $postsTableWrapper = $('#postsTableWrapper');
            var $postPreviewContainer = $('#postPreviewContainer');

            // open sweetalert when delete button clicked
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                deleteUrl = $(this).data('url');
                $rowToDelete = $(this).closest('tr');

                swal({
                    title: "Confirmar eliminación",
                    text: "¿Estás seguro que deseas eliminar esta noticia? Esta acción no se puede deshacer.",
                    icon: "warning",
                    buttons: ["No", "Sí, eliminar"],
                    dangerMode: true,
                }).then(function(willDelete) {
                    if (willDelete) {
                        // perform AJAX delete
                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                // remove row from DataTable
                                table.row($rowToDelete).remove().draw();
                                swal("Eliminado", "La noticia fue eliminada correctamente", "success");
                            },
                            error: function(xhr) {
                                swal("Error", "No se pudo eliminar la noticia", "error");
                            }
                        });
                    }
                });
            });

            // Preview button click (AJAX load into modal)
            $(document).on('click', '.btn-preview', function(e) {
                e.preventDefault();
                var url = $(this).data('url');

                // Hide table and show loading preview container
                $postsTableWrapper.hide();
                $postPreviewContainer.html('<div class="text-center">Cargando...</div>').show();

                // Load via AJAX into the preview container
                $.get(url, function(html) {
                    $postPreviewContainer.html(html);
                }).fail(function() {
                    $postPreviewContainer.html('<div class="alert alert-danger">No se pudo cargar la vista previa.</div>');
                });
            });

            // Back button inside preview container
            $(document).on('click', '#btn-back-to-list', function(e) {
                e.preventDefault();
                $postPreviewContainer.hide().html('');
                $postsTableWrapper.show();
            });
        });
    </script>
@endsection