@extends('layouts.error')

@section('title','Error interno')

@section('code')
500
@endsection

@section('code_text','Error 500')

@section('message')
Lo sentimos, ha ocurrido un error en el servidor. Por favor inténtalo más tarde.
@endsection

@section('details')
@if(config('app.debug'))
  <h4>Detalles (solo en desarrollo)</h4>
  <pre>{{  }}</pre>
@endif
@endsection
