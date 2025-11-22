@extends('layouts.auth')

@section('title', 'Ver Trámite')

@section('section')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> {{ $tramite->title_short }} </h3>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if($tramite->logo_image)
                            <img src="{{ asset($tramite->logo_image) }}" alt="logo" style="max-width:120px;">
                        @elseif($tramite->logo_class)
                            <i class="{{ $tramite->logo_class }}" style="font-size:48px"></i>
                        @endif

                        <h4 class="mt-3">{{ $tramite->title_full }}</h4>

                        <p>{{ $tramite->description }}</p>

                        <div class="mt-4">
                            {!! $tramite->content !!}
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('tramites.index') }}" class="btn btn-light">Volver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
