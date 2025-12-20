@extends('layouts.auth')

@section('title', 'Slider de Noticias')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Slider de Noticias</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><span>Noticias</span></li>
                <li class="breadcrumb-item active" aria-current="page">Slider Home</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('newsslider.store') }}" id="newsSliderForm">
                        @csrf
                        <input type="hidden" name="add_posts" id="add_posts_input">
                        <input type="hidden" name="remove_posts" id="remove_posts_input">

                        <div class="accordion" id="newsSliderAccordion">
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="newsSliderGeneralHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#newsSliderGeneral" aria-expanded="true" aria-controls="newsSliderGeneral">
                                        General
                                    </button>
                                </h2>
                                <div id="newsSliderGeneral" class="accordion-collapse collapse show" aria-labelledby="newsSliderGeneralHeading" data-bs-parent="#newsSliderAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label for="title" class="form-label">Título de la sección</label>
                                                <input id="title" name="title" type="text" class="form-control" value="{{ old('title', optional($settings)->title ?? 'Noticias y Actividades para ti') }}">
                                            </div>
                                            <div class="col-md-8">
                                                <label for="subtitle" class="form-label">Subtítulo</label>
                                                <input id="subtitle" name="subtitle" type="text" class="form-control" value="{{ old('subtitle', optional($settings)->subtitle) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="newsSliderListHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#newsSliderList" aria-expanded="false" aria-controls="newsSliderList">
                                        Noticias en el slider
                                    </button>
                                </h2>
                                <div id="newsSliderList" class="accordion-collapse collapse" aria-labelledby="newsSliderListHeading" data-bs-parent="#newsSliderAccordion">
                                    <div class="accordion-body">
                                        <div class="d-flex justify-content-end mb-2">
                                            <button type="button" class="btn btn-sm btn-gradient-primary" id="btn-open-modal">Agregar noticias</button>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Imagen</th>
                                                        <th>Título</th>
                                                        <th class="text-center">Estado</th>
                                                        <th class="text-center">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="slider-table-body">
                                                    @forelse($sliderPosts as $post)
                                                        <tr data-id="{{ $post->id }}">
                                                            <td style="width:120px;">
                                                                <img src="{{ $post->gallery->image ?? asset('assets/website/images/default-news.jpg') }}" alt="" style="max-width:100px; width: 55px; height:60px; object-fit:cover;">
                                                            </td>
                                                            <td>{{ $post->title }}</td>
                                                            <td class="text-center">
                                                                @if($post->is_news_slider)
                                                                    <i class="mdi mdi-check text-success"></i>
                                                                @else
                                                                    <i class="mdi mdi-close text-danger"></i>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-sm btn-danger btn-remove-slider" data-id="{{ $post->id }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="4" class="text-center">No hay noticias en el slider.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-2">
                                            {{ $sliderPosts->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('posts.index') }}" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-gradient-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal agregar noticias -->
<div class="modal fade" id="newsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-auto modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar noticias al slider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="modal-table-wrapper">
                    @include('auth.newsslider.partials.table', ['posts' => $availablePosts])
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-gradient-primary" id="btn-add-selected">Agregar seleccionadas</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/website/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('assets/website/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script>
        (function(){
            const postsData = @json($availablePosts->map(fn($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'img' => $p->gallery->image ?? asset('assets/website/images/default-news.jpg')
            ]));

            const selectedAdd = new Set();
            const selectedRemove = new Set();

            const addInput = document.getElementById('add_posts_input');
            const removeInput = document.getElementById('remove_posts_input');
            const sliderTableBody = document.getElementById('slider-table-body');

            function syncInputs() {
                addInput.value = Array.from(selectedAdd).join(',');
                removeInput.value = Array.from(selectedRemove).join(',');
            }

            const modalEl = document.getElementById('newsModal');
            const openBtn = document.getElementById('btn-open-modal');
            let dt;

            function applyChecks() {
                $('#modal-posts-table').find('.modal-select').each(function(){
                    const id = parseInt(this.dataset.id);
                    this.checked = selectedAdd.has(id);
                });
            }

            function initDT() {
                dt = $('#modal-posts-table').DataTable({
                    pageLength: 8,
                    lengthChange: false,
                    searching: true,
                    ordering: false,
                    info: false,
                    language: { url: '{{ asset('assets/website/plugins/datatables/lang/es-ES.json') }}' }
                });
                applyChecks();
                dt.on('draw', applyChecks);

                $('#modal-posts-table').off('change').on('change', '.modal-select', function(){
                    const id = parseInt(this.dataset.id);
                    if (this.checked) {
                        selectedAdd.add(id);
                        selectedRemove.delete(id);
                    } else {
                        selectedAdd.delete(id);
                    }
                    syncInputs();
                });
            }

            function ensureDT() {
                if (!dt) {
                    initDT();
                }
            }

            function rowExists(id) {
                return sliderTableBody.querySelector(`tr[data-id="${id}"]`);
            }

            function appendToSlider(id, title, img) {
                if (rowExists(id)) return;
                sliderTableBody.insertAdjacentHTML('beforeend', `
                    <tr data-id="${id}">
                        <td style="width:120px;">
                            <img src="${img}" alt="" style="max-width:100px; width:55px; height:60px; object-fit:cover;">
                        </td>
                        <td>${title}</td>
                        <td class="text-center"><i class="mdi mdi-check text-success"></i></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger btn-remove-slider" data-id="${id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            }

            openBtn.addEventListener('click', function(){
                new bootstrap.Modal(modalEl).show();
                ensureDT();
            });

            document.getElementById('btn-add-selected').addEventListener('click', function(){
                ensureDT();
                selectedAdd.forEach(id => {
                    const data = postsData.find(p => p.id === id);
                    if (!data) return;
                    appendToSlider(id, data.title, data.img);
                });
                bootstrap.Modal.getInstance(modalEl).hide();
                syncInputs();
            });

            document.getElementById('slider-table-body').addEventListener('click', function(e){
                const btn = e.target.closest('.btn-remove-slider');
                if (!btn) return;
                const row = btn.closest('tr');
                const id = parseInt(btn.dataset.id);
                const title = row.querySelector('td:nth-child(2)').textContent.trim();
                const img = row.querySelector('img')?.src ?? '';

                selectedRemove.add(id);
                selectedAdd.delete(id);
                row.remove();

                ensureDT();
                dt.row.add([
                    `<div class=\"form-check form-switch\"><input type=\"checkbox\" class=\"form-check-input modal-select\" data-id=\"${id}\"></div>`,
                    `<img src=\"${img}\" style=\"max-width:100px; height:60px; object-fit:cover;\">`,
                    title
                ]).draw(false);
                applyChecks();
                syncInputs();
            });

            syncInputs();
        })();
    </script>
@endsection