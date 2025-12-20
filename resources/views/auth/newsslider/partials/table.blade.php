@php $defaultImg = asset('assets/website/images/default-news.jpg'); @endphp
<table class="table table-sm table-hover align-middle" id="modal-posts-table">
    <thead>
        <tr>
            <th style="width:60px;">Seleccionar</th>
            <th style="width:120px;">Imagen</th>
            <th>Título</th>
        </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
            <tr data-id="{{ $post->id }}">
                <td>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input modal-select" data-id="{{ $post->id }}">
                    </div>
                </td>
                <td><img src="{{ $post->gallery->image ?? $defaultImg }}" alt="" style="max-width:100px; width:55px; height:60px; object-fit:cover;"></td>
                <td>{{ $post->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
