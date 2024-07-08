@extends('layouts.admin.app')

@section('title', 'Edit Page Blocks')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="float-left">
                    <h1 class="m-0">Edit Page #{{$page->id}} Blocks</h1>
                </div>
                <div class="float-left pl-3">
                    <a href="#" class="btn btn-primary collapse-all">
                        Collapse
                    </a>
                    <a href="#" class="btn btn-primary unwrap-all">
                        Uncollapse
                    </a>
                </div>
            </div>
        </div>
    </div>
    
@stop

@section('content')
    <form id="blocksForm" action="{{ route('admin.pages.update-blocks', $page) }}" method="post" class="general-ajax-submit" style="padding-bottom:1.5rem">
        @csrf
        @method('PUT')
        @foreach($itemGroups as $itemGroup => $items)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{readable($itemGroup)}}
                    </h3>
                    <div class="card-tools">
                        <small style="color: rgb(188, 188, 188)">{{$items->count()}}</small>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($items as $item)
                        <div class="form-group">
                            <label>{{ str_replace('_', ' ', ucfirst($item->name)) }}</label>
                            @switch($item->type->value)
                                @case(\App\Enums\PageItemType::TEXT->value)
                                    <x-admin.multi-lang-input name="items[{{ $item->id }}]" :value="$item->values" />
                                @break

                                @case(\App\Enums\PageItemType::TEXTAREA->value)
                                    <x-admin.multi-lang-input name="items[{{ $item->id }}]" :value="$item->values" textarea="1" />
                                @break

                                @case(\App\Enums\PageItemType::RICHTEXT->value)
                                    <x-admin.multi-lang-input name="items[{{ $item->id }}]" :value="$item->values" richtext="1" />
                                @break

                                @default
                            @endswitch
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/pages.js')}}"></script>
@endpush