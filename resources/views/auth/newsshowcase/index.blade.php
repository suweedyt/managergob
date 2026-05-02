@extends('layouts.auth')

@section('title', 'Aparador de noticias')

@section('section')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Aparador de noticias</h3>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(session('alert-success'))
                        <div class="alert alert-success">{{ session('alert-success') }}</div>
                    @endif

                    @php $ns = $settings ?? null; @endphp

                    <div class="accordion mb-3" id="showcaseSettingsAccordion">
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="headingSettings">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSettings" aria-expanded="true" aria-controls="collapseSettings">
                                    Configuración del Aparador
                                </button>
                            </h2>
                            <div id="collapseSettings" class="accordion-collapse collapse show" aria-labelledby="headingSettings" data-bs-parent="#showcaseSettingsAccordion">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Título</label>
                                            <input type="text" name="title" class="form-control" value="{{ old('title', $ns->title ?? '') }}" placeholder="Título del aparador">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Subtítulo</label>
                                            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $ns->subtitle ?? '') }}" placeholder="Subtítulo del aparador">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="headingSettingsList">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSettingsList" aria-expanded="false" aria-controls="collapseSettingsList">
                                    Listado de noticias en el aparador
                                </button>
                            </h2>
                            <div id="collapseSettingsList" class="accordion-collapse collapse" aria-labelledby="headingSettingsList" data-bs-parent="#showcaseSettingsAccordion">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h4 class="card-title mb-0">Noticias en el aparador</h4>
                                            <button type="button" class="btn btn-gradient-primary" id="btn-open-modal">Agregar noticias</button>
                                        </div>
                                        <div class="col-12">
                                            <form method="POST" action="{{ route('newsshowcase.store') }}">
                                                @csrf
                                                <input type="hidden" name="add_posts" id="add_posts_input">
                                                <input type="hidden" name="remove_posts" id="remove_posts_input">

                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Imagen</th>
                                                                <th>Título</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="showcase-table-body">
                                                            @foreach($items as $item)
                                                                <tr data-id="{{ $item->post->id }}">
                                                                    <td style="width:120px;">
                                                                        <img src="{{ asset(optional($item->post->gallery)->image ?? 'assets/website/images/default-news.jpg') }}" alt="" style="max-width:100px; width:55px; height:60px; object-fit:cover;">
                                                                    </td>
                                                                    <td>{{ $item->post->title }}</td>
                                                                    <td class="d-flex gap-2 align-items-center">
                                                                        <button type="button" class="btn btn-sm btn-danger btn-remove-showcase" data-id="{{ $item->post->id }}">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>

                                                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-size" data-id="{{ $item->id }}">
                                                                            @if($item->is_large)
                                                                                <i class="fas fa-expand-arrows-alt" title="Grande"></i>
                                                                            @else
                                                                                <i class="fas fa-compress-arrows-alt" title="Pequeña"></i>
                                                                            @endif
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
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
                    </div>

                    <div class="mt-3">
                        {{ $items->links() }}
                    </div>
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
                <h5 class="modal-title">Agregar noticias al aparador</h5>
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
            const selectedAdd = new Set();
            const selectedRemove = new Set();

            const addInput = document.getElementById('add_posts_input');
            const removeInput = document.getElementById('remove_posts_input');
            const tableBody = document.getElementById('showcase-table-body');

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
                if (dt) {
                    dt.destroy();
                }
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

            function appendRow(id, title, img) {
                if (tableBody.querySelector(`tr[data-id="${id}"]`)) return;
                tableBody.insertAdjacentHTML('afterbegin', `
                    <tr data-id="${id}">
                        <td style=\"width:120px;\"><img src=\"${img}\" style=\"max-width:100px; width:55px; height:60px; object-fit:cover;\"></td>
                        <td>${title}</td>
                        <td><button type=\"button\" class=\"btn btn-sm btn-danger btn-remove-showcase\" data-id=\"${id}\"><i class=\"fas fa-trash\"></i></button></td>
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
                    const row = $('#modal-posts-table').find(`.modal-select[data-id="${id}"]`).closest('tr');
                    const title = row.find('td:nth-child(3)').text().trim();
                    const img = row.find('img').attr('src') || '';
                    appendRow(id, title, img);
                });
                bootstrap.Modal.getInstance(modalEl).hide();
                syncInputs();
            });

            tableBody.addEventListener('click', function(e){
                const btn = e.target.closest('.btn-remove-showcase');
                if (!btn) return;
                const row = btn.closest('tr');
                const id = parseInt(btn.dataset.id);
                const title = row.querySelector('td:nth-child(2)').textContent.trim();
                const img = row.querySelector('img')?.src ?? '';
                row.remove();

                selectedRemove.add(id);
                selectedAdd.delete(id);

                ensureDT();
                dt.row.add([
                    `<div class=\"form-check form-switch\"><input type=\"checkbox\" class=\"form-check-input modal-select\" data-id=\"${id}\"></div>`,
                    `<img src=\"${img}\" style=\"max-width:100px; height:60px; object-fit:cover;\">`,
                    title
                ]).draw(false);
                applyChecks();
                syncInputs();
            });

            document.querySelectorAll('.btn-toggle-size').forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.dataset.id;
                    fetch("{{ url('auth/newsshowcase') }}/" + id + "/toggle-size", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    }).then(r => r.json()).then(json => {
                        if (json.ok) {
                            const icon = this.querySelector('i');
                            if (json.is_large) {
                                icon.className = 'fas fa-expand-arrows-alt';
                                icon.title = 'Grande';
                            } else {
                                icon.className = 'fas fa-compress-arrows-alt';
                                icon.title = 'Pequeña';
                            }
                        } else {
                            alert('Error al actualizar tamaño');
                        }
                    }).catch(() => alert('Error de conexión'));
                });
            });

            syncInputs();
        })();
    </script>
@endsection
