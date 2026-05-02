@extends('layouts.auth')

@section('title', 'Dashboard')

@section('section')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white">
                    <div class="card-body">
                        <h4 class="font-weight-normal mb-3">Noticias <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ $postCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                        <h4 class="font-weight-normal mb-3">Categorias <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ $categoryCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                    <div class="card-body">
                        <h4 class="font-weight-normal mb-3">Usuarios <i class="mdi mdi-diamond mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ $usersCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-warning card-img-holder text-white">
                    <div class="card-body">
                        <h4 class="font-weight-normal mb-3">Trámites <i class="mdi mdi-file-document-outline mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ $tramiteCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-primary card-img-holder text-white">
                    <div class="card-body">
                        <h4 class="font-weight-normal mb-3">Secciones <i class="mdi mdi-view-sequential-outline mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ $sectionsCount }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection