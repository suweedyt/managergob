@extends('layouts.auth')

@section('title', 'Administrar Banners')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/datatables/jquery.dataTables.min.css') }}" />
@endsection

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Banners </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Banners</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Todos</li>
                </ol>
            </nav>
        </div>
        <div class="row mb-3">
            <div class="container btn-addnew">
                <a href="{{ route('banners.create') }}" class="btn btn-gradient-primary me-2">Nuevo Banner</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if (count($banners) > 0)
                            <div id="bannersTableWrapper">
                                <table id="banners-table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Media</th>
                                            <th>Titulo</th>
                                            <th>Publicado</th>
                                            <th>Tipo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($banners as $banner)
                                            <tr>
                                                <td class="py-1 text-center">
                                                    @if ($banner->media_type === 'video')
                                                        @php
                                                            $path = $banner->media_path ?? '';
                                                            $isUrl = \Illuminate\Support\Str::startsWith($path, ['http://','https://']);
                                                            $isYouTube = $isUrl && preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/', $path);
                                                        @endphp

                                                        @if($isYouTube)
                                                            @php preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/', $path, $m); $vid = $m[1] ?? null; @endphp
                                                            <img src="https://img.youtube.com/vi/{{ $vid }}/hqdefault.jpg" alt="thumb" style="max-width:120px; max-height:80px; object-fit:cover;" />
                                                        @else
                                                            @if($isUrl)
                                                                {{-- external video URL: show generic icon --}}
                                                                <div class="video-icon" style="font-size:28px;color:#6c757d;"><i class="fas fa-video"></i></div>
                                                            @else
                                                                {{-- local video: try to show a generated thumbnail via client-side capture; fallback to icon --}}
                                                                <img id="thumb-{{ $banner->id }}" src="" alt="thumb" style="max-width:120px; max-height:80px; object-fit:cover; display:none;" />
                                                                <video id="video-src-{{ $banner->id }}" src="{{ asset(ltrim($banner->media_path, '/')) }}" style="display:none;" preload="metadata"></video>
                                                                <div id="icon-{{ $banner->id }}" class="video-icon" style="font-size:28px;color:#6c757d;"><i class="fas fa-video"></i></div>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <img src="{{ asset(ltrim($banner->media_path, '/')) }}" alt="image" style="max-width:120px; max-height:80px; object-fit:cover;" />
                                                    @endif
                                                </td>
                                                <td>{{ $banner->title }}</td>
                                                <td class="text-center">@if($banner->is_published) <span class="mdi mdi-check"></span> @else <span class="mdi mdi-close"></span> @endif</td>
                                                <td class="text-capitalize">{{ $banner->media_type }}</td>
                                                <td>
                                                    <a href="{{ route('banners.edit', $banner) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                                    <a href="{{ url('auth/banners/preview') . '?media_type=' . $banner->media_type . '&media_path=' . urlencode($banner->media_path) . '&position_x=' . ($banner->position_x ?? 50) . '&position_y=' . ($banner->position_y ?? 50) . '&title=' . urlencode($banner->title) }}" class="btn btn-sm btn-secondary" style="margin-left:6px;"><i class="fas fa-eye"></i></a>
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ route('banners.destroy', $banner) }}"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-primary text-center" role="alert">
                                No hay banners
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
        $(function() {
            var table = $('#banners-table').DataTable({
                language: { url: '{{ asset("assets/website/plugins/datatables/lang/es-ES.json") }}' }
            });

            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).data('url');
                var $rowToDelete = $(this).closest('tr');

                swal({
                    title: "Confirmar eliminacion",
                    text: "Eliminar este banner? Esta accion no se puede deshacer.",
                    icon: "warning",
                    buttons: ["No", "Si, eliminar"],
                    dangerMode: true,
                }).then(function(willDelete) {
                    if (willDelete) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                            success: function() {
                                table.row($rowToDelete).remove().draw();
                                swal("Eliminado", "El banner fue eliminado correctamente", "success");
                            },
                            error: function() {
                                swal("Error", "No se pudo eliminar el banner", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

@section('scripts')
    <script>
        // Attempt to capture a frame from local videos to use as thumbnail (same-origin only)
        document.addEventListener('DOMContentLoaded', function() {
            function captureThumbnail(videoEl, imgEl, iconEl) {
                try {
                    videoEl.currentTime = 0.5;
                    videoEl.addEventListener('loadeddata', function handler() {
                        try {
                            var canvas = document.createElement('canvas');
                            canvas.width = videoEl.videoWidth || 320;
                            canvas.height = videoEl.videoHeight || 180;
                            var ctx = canvas.getContext('2d');
                            ctx.drawImage(videoEl, 0, 0, canvas.width, canvas.height);
                            var dataUrl = canvas.toDataURL('image/jpeg');
                            imgEl.src = dataUrl;
                            imgEl.style.display = '';
                            if(iconEl) iconEl.style.display = 'none';
                        } catch (err) {
                            // cannot capture (cross-origin or other), leave icon
                            if(iconEl) iconEl.style.display = '';
                        }
                        videoEl.removeEventListener('loadeddata', handler);
                    });
                    // trigger load
                    videoEl.load();
                } catch (err) {
                    if(iconEl) iconEl.style.display = '';
                }
            }

            // find all video-src-* elements
            document.querySelectorAll('video[id^="video-src-"]').forEach(function(v) {
                var id = v.id.replace('video-src-','');
                var img = document.getElementById('thumb-' + id);
                var icon = document.getElementById('icon-' + id);
                if(img && v) captureThumbnail(v, img, icon);
            });
        });

        // Preview modal handling
        (function(){
            function isYouTubeUrl(url){
                return /(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/.test(url);
            }

            $(document).on('click', '.btn-preview', function(){
                var $btn = $(this);
                var mediaType = $btn.data('media-type');
                var mediaPath = $btn.data('media-path') || '';
                var posX = $btn.data('position-x') || 50;
                var posY = $btn.data('position-y') || 50;
                var title = $btn.data('title') || '';

                // build modal content
                var $modal = $('#bannerPreviewModal');
                $modal.find('.modal-title').text(title);
                var $body = $modal.find('.modal-body');
                $body.empty();

                if(mediaType === 'image'){
                    var $div = $('<div/>').css({
                        'background-image': 'url("' + (mediaPath.startsWith('http') ? mediaPath : ('/' + mediaPath.replace(/^\/+/, ''))) + '")',
                        'background-size': 'cover',
                        'background-position': posX + '% ' + posY + '%',
                        'height': '520px'
                    });
                    $body.append($div);
                } else if(mediaType === 'video'){
                    if(mediaPath && mediaPath.startsWith('http') && isYouTubeUrl(mediaPath)){
                        // iframe YouTube
                        var ytId = mediaPath.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/)[1];
                        var $iframe = $('<iframe/>', {
                            src: 'https://www.youtube.com/embed/' + ytId + '?rel=0&autoplay=1&mute=1&playsinline=1',
                            width: '100%', height: '520', frameborder: 0, allow: 'autoplay; encrypted-media; picture-in-picture', allowfullscreen: true
                        }).css('border',0);
                        $body.append($iframe);
                    } else {
                        // local or direct video
                        var src = mediaPath.startsWith('http') ? mediaPath : ('/' + mediaPath.replace(/^\/+/, ''));
                        var $video = $('<video/>', {
                            src: src,
                            controls: true,
                            autoplay: true,
                            muted: true,
                            loop: true
                        }).css({width: '100%', height: '520px', objectFit: 'cover', objectPosition: posX + '% ' + posY + '%'});
                        $body.append($video);
                    }
                }

                $modal.modal('show');
            });
        })();
    </script>
@endsection

<!-- Preview Modal -->
<div class="modal fade" id="bannerPreviewModal" tabindex="-1" role="dialog" aria-labelledby="bannerPreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding:0;">
        <!-- Content injected dynamically -->
      </div>
    </div>
  </div>
</div>
