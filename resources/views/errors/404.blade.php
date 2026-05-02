@extends('layouts.error')

@section('title','Página no encontrada')

@section('code')
404
@endsection

@section('code_text','Error 404')

@section('message')
La página que buscas no existe o fue movida. Verifica la URL e inténtalo nuevamente.
@endsection

@section('details')
@if(config('app.debug'))
  <h4>Detalles (solo en desarrollo)</h4>
  <pre>
    @if(isset($exception) && method_exists($exception, 'getMessage'))
      {{ $exception->getMessage() }}
    @else
      {{ request()->fullUrl() }}
    @endif
  </pre>
@endif
@endsection
