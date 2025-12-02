@extends('layouts.website')

@section('title', $tramite->title_short ?? 'Trámite')

@section('content')
    <div class="page-wrapper">
        <div class="container py-4">
            <div class="row">
                <h1>{{ $tramite->title_short }}</h1>
                <p class="lead">{{ $tramite->description }}</p>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    @php
                        $url = $tramite->redirect_url;
                        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                    @endphp

                    @if ($ext === 'pdf')
                        <div style="height:80vh;">
                            <iframe src="{{ $url }}" style="width:100%; height:100%; border: none;" title="Documento PDF"></iframe>
                        </div>
                    @else
                        <div class="card p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>Enlace configurado:</strong>
                                    <div class="small text-muted">{{ $url }}</div>
                                </div>
                                <div>
                                    <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary">Abrir en nueva pestaña</a>
                                </div>
                            </div>
                            <div style="height:70vh;">
                                <iframe src="{{ $url }}" style="width:100%; height:100%; border: none;" title="Contenido"></iframe>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
