@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.user.mailer')}}</title>
	<meta name="description" content="{{__('meta.description.user.mailer')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">{{__('ui.imports')}}</span>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='import'/>
        <div class="content">
            <h1>{{__('ui.imports')}} (<span class="orange">{{$imports->count()}}</span>)</h1>
            <div class="history">
                <div class="history-top">
                    <div class="history-title">
                        {{__('ui.history')}}
                    </div>
                    <div class="history-title">
                        <a href="{{route('imports.create')}}">Create</a>
                    </div>
                </div>
                <div class="history-table">
                    <table>
                        <tr>
                            <th>№</th>
                            <th>{{__('ui.date')}}</th>
                            <th>{{__('ui.name')}}</th>
                            <th>{{__('ui.posts')}}</th>
                            <th>{{__('ui.status')}}</th>
                            <th></th>
                        </tr>
                        @foreach ($imports as $i => $import)
                            <tr>
                                <td>{{$imports->count() - $i}}</td>
                                <td>
                                    {{$import->created_at->format('d M, Y')}}
                                    <span class="history-table-date">{{$import->created_at->format('H:i')}}</span>
                                </td>
                                <td><a href="{{route('imports.download', $import)}}">{{$import->file->original_name}}</a></td>
                                <td>
                                    {{count($import->posts??[])}}
                                    @if (count($import->posts??[]))
                                        <span class="history-table-date">
                                            <a href="{{route('imports.posts', $import)}}" class="see-import-posts">See</a>
                                        </span>
                                    @endif
                                </td>
                                <td>{{$import->status}}</td>
                                <td>
                                    @if ($import->status == 'done' && count($import->posts??[]))
                                        <form action="{{route('imports.posts.delete', $import)}}" method="post" class="general-ajax-submit ask" data-asktitle="Are you sure?" data-asktext="You won't be able to revert this!" data-askno="Cancel" data-askyes="Yes, delete all">{{-- //! TRANSLATE --}}
                                            @csrf
                                            <button type="submit">Delete all</button>
                                        </form>
                                        <form action="{{route('imports.posts.deactivate', $import)}}" method="post" class="general-ajax-submit">
                                            @csrf
                                            <button type="submit">Deactivate all</button>
                                        </form>
                                        <form action="{{route('imports.posts.activate', $import)}}" method="post" class="general-ajax-submit">
                                            @csrf
                                            <button type="submit">Activate all</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
