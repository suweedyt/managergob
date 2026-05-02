@extends('layouts.website')

@section('title', $new ? $new->title : 'Detalle de la Noticia')

@section('content')
    <section class="page-wrapper">
        <div class="container">
            <div class="back-list-news">
                <a href="{{ route('news') }}">
                    <i class="ion-arrow-return-left"></i>
                    <span>Ver Todas</span>
                </a>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="post post-single">
                        <h2 class="post-title">{{ $new ? $new->title : '' }}</h2>
                        <div class="post-meta">
                            <ul>
                                <li>
                                    <i class="ion-calendar"></i> {{ date('d M Y', strtotime($new->created_at)); }}
                                </li>
                            </ul>
                        </div>
                        <div class="post-thumb">
                            <img class="img-fluid" src="{{ $new ? $new->gallery->image : '' }}" alt="">
                        </div>
                        <div class="post-content post-excerpt">
                            <div class="rich-content"> {!! $new ? $new->description : '' !!} </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endSection