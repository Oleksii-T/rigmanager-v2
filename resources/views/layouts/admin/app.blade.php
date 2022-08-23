@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="/css/admin/custom.css">
    @stack('styles')
@stop

@section('js')
    <script src="/js/admin/custom.js"></script>
    @stack('scripts')
@stop
