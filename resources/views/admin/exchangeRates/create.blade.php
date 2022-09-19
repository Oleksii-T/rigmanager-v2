@extends('layouts.admin.app')

@section('title', 'Create Rate')

@section('content_header')
    <x-admin.title
        text="Create Rate"
    />
@stop

@section('content')
    <form action="{{route('admin.exchange-rates.sync')}}" method="POST" class="general-ajax-submit">
        @csrf
        <div class="card">
            <div class="card-body">
                <p>To create a new Exchange rate follow instructions:</p>
                <ul>
                    <li>Add new currency to "/config/currencies.php" file</li>
                    <li>Initiate rates for new currency by clicking "Sync rates with currencies" button bellow</li>
                    <li>Find and set up rate manualy from <a href="{{route('admin.exchange-rates.index')}}">Exchange rates</a> page</li>
                </ul>
            </div>
        </div>

        <button type="submit" class="btn btn-success min-w-100">Sync rates with currencies</button>
        <a href="{{ route('admin.exchange-rates.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
