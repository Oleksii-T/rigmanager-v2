@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="/css/admin/custom.css">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" type='text/css'>

    @stack('styles')
@stop

@section('js')
    <script src="/js/admin/custom.js"></script>
    @stack('scripts')
@stop
