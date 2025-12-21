@extends ('layouts.website')

@section('title', 'Noticias y Actividades')

@section ('content')
    <div class="page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    @if (count($posts) > 0)
                        @foreach ($posts as $post)
                            <div class="post">
                                <h3 class="post-title">
                                    <a href="{{ route('news.single', $post->id) }}">{{ $post->title }}</a>
                                </h3>
                                <div class="post-meta">
                                    <ul>
                                        <li>
                                            <i class="ion-calendar"></i>
                                            {{ $post->created_at->locale('es')->isoFormat('D [de] MMMM YYYY') }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="post-media post-thumb">
                                    <a href="{{ route('news.single', $post->id) }}">
                                        <img src="{{ $post->gallery->image }}" alt="">
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="rich-content"> {!! limitHtml($post->description, 150, '...') !!} </div>
                                    <div><a href="{{ route('news.single', $post->id) }}" class="btn btn-main">Saber M&aacute;s</a></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="widget-latest-post-wrap">
                            <div class="widget-latest-post-item">
                                <h5>No hay noticias publicadas</h5>
                            </div>
                        </div>
                    @endif

                    {{ $posts->links('pagination.default') }}
                </div>
                <div class="col-lg-4">
                    <div class="pl-0 pl-xl-4">
                        <aside class="sidebar pt-5 pt-lg-0 mt-5 mt-lg-0">
                            <!-- Archive by Month -->
                            <div class="widget widget-archive mb-4">
                                <h4 class="widget-title">Filtrar por Fecha</h4>
                                <ul class="list-unstyled">
                                    @if(!empty($archiveMonths) && count($archiveMonths) > 0)
                                        @foreach($archiveMonths as $m)
                                            @php
                                                $qs = array_merge(request()->except(['page','month','year']), ['month' => $m['month'], 'year' => $m['year']]);
                                                $url = url()->current() . (count($qs) ? ('?' . http_build_query($qs)) : '');
                                                $active = (request()->get('month') == $m['month'] && request()->get('year') == $m['year']);
                                            @endphp
                                            <li>
                                                <a href="{{ $url }}" class="btn btn-sm btn-outline-secondary mb-1 {{ $active ? 'active' : '' }}" role="button">{{ $m['label'] }}</a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li>No hay fechas</li>
                                    @endif
                                    </ul>
                                    @if(request()->has('month') || request()->has('year'))
                                        @php $clearDateQs = request()->except(['month','year','page']); @endphp
                                        @if(count($clearDateQs) > 0)
                                            @php $clearUrl = url()->current() . (count($clearDateQs) ? ('?' . http_build_query($clearDateQs)) : ''); @endphp
                                        @else
                                            @php $clearUrl = url()->current(); @endphp
                                        @endif
                                        <div class="mt-2"><a href="{{ $clearUrl }}" class="text-muted">Limpiar fecha</a></div>
                                    @endif
                            </div>

                            <!-- Categories Filter -->
                            <div class="widget widget-categories mb-4">
                                <h4 class="widget-title">Filtrar por Categoría</h4>
                                <ul class="list-unstyled">
                                    @if(!empty($categories) && count($categories) > 0)
                                            @foreach($categories as $cat)
                                                @php
                                                    $qs = array_merge(request()->except(['page','category']), ['category' => $cat->id]);
                                                    $url = url()->current() . (count($qs) ? ('?' . http_build_query($qs)) : '');
                                                    $active = (request()->get('category') == $cat->id);
                                                @endphp
                                                <li>
                                                    <a href="{{ $url }}" class="btn btn-sm btn-outline-secondary mb-1 {{ $active ? 'active' : '' }}" role="button">{{ $cat->name }}</a>
                                                </li>
                                            @endforeach
                                    @else
                                        <li>No hay categorías</li>
                                    @endif
                                </ul>
                                @if(request()->has('category'))
                                    @php $clearCatQs = request()->except(['category','page']); @endphp
                                    @if(count($clearCatQs) > 0)
                                        @php $clearCatUrl = url()->current() . (count($clearCatQs) ? ('?' . http_build_query($clearCatQs)) : ''); @endphp
                                    @else
                                        @php $clearCatUrl = url()->current(); @endphp
                                    @endif
                                    <div class="mt-2"><a href="{{ $clearCatUrl }}" class="text-muted">Limpiar categoría</a></div>
                                @endif
                                
                            </div>

                            <!-- Widget others news -->
                            <div class="widget widget-latest-post">
                                <h4 class="widget-title">Otras Noticias</h4>
                                @if (count($latestsPosts) > 0)
                                    @foreach ($latestsPosts as $latestPost)
                                        <div class="media">
                                            <a class="pull-left" href="{{ route('news.single', $latestPost->id) }}">
                                                <img class="media-object" src="{{ $latestPost->gallery->image }}" alt="Image">
                                            </a>
                                            <div class="media-body">
                                                <h4 class="media-heading">
                                                    <a href="{{ route('news.single', $latestPost->id) }}">{{ $latestPost->title }}</a>
                                                </h4>
                                                <div class="rich-content">{!! limitHtml($latestPost->description, 15, '...') !!}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="widget-latest-post-wrap">
                                        <div class="widget-latest-post-item">
                                            <h5>No hay noticias publicadas</h5>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <!-- End Latest Posts -->

                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection