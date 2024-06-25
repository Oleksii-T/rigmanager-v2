@extends('layouts.admin.app')

@section('title', 'Scrapers')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <div class="float-left">
                    <h1 class="m-0">Publishing of scraped Posts from run #{{$scraperRun->id}} of '{{$scraper->name}}' scraper</h1>
                </div>
                <div class="float-left pl-3">
                    <a href="{{route('admin.scraper-runs.show', $scraperRun)}}" class="btn btn-primary">
                        Back to #{{$scraperRun->id}} run
                    </a>
                    <a href="{{route('admin.scrapers.show', $scraper)}}" class="btn btn-primary">
                        Back to '{{$scraper->name}}' scraper
                    </a>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#progress">
                        Progress 
                        <small>{{$posts->where('status', '!=', \App\Enums\ScraperPostStatus::PENDING)->count()}} / {{$posts->count()}}</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@push('styles')
    <style>
        .scraped-images {
            position: relative;
        }
        .scraped-images div {
            position: absolute;
            width: 400px;
            top: 35px;
            z-index: 2;
            border: 2px solid white;
            display: none;
        }
        .scraped-images:hover div {
            display: block;
        }
        .scraped-images img {
            width: 100%;
        }
    </style> 
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-7">
            <form action="{{route('admin.scraper-posts.publish', $scraperPost)}}" method="post" class="card general-ajax-submit" style="margin-bottom: 0px">
                @csrf
                <input type="hidden" name="created_post_id" value="{{$alreadyPublishedPost?->id}}">
                <div class="card-header">
                    @if ($alreadyPublishedPost)
                        <h3 class="card-title">
                            Published Post <a href="{{route('admin.posts.edit', $alreadyPublishedPost)}}" target="_blank">#{{$alreadyPublishedPost->id}}</a>
                        </h3>
                        <span style="float: right">{{$alreadyPublishedPost->status}}</span>
                    @else
                        <h3>New Post</h3>
                    @endif
                </div>
                <div class="card-body" style="height:74vh;overflow-y:auto;">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Title</label>
                                <x-admin.multi-lang-input name="title" :model="$alreadyPublishedPost" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <x-admin.multi-lang-input name="description" :model="$alreadyPublishedPost" richtextPostsDesc="1" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type">
                                    @foreach (\App\Enums\PostType::all() as $key => $name)
                                        <option value="{{$key}}" @selected($alreadyPublishedPost?->type->value == $key)>{{$name}}</option>
                                    @endforeach
                                </select>
                                <span data-input="type" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Condition</label>
                                <select class="form-control" name="condition">
                                    @foreach (\App\Models\Post::CONDITIONS as $item)
                                        <option value="{{$item}}" @selected($alreadyPublishedPost?->condition == $item)>{{readable($item)}}</option>
                                    @endforeach
                                </select>
                                <span data-input="condition" class="input-error"></span>
                            </div>
                        </div>
                        @include('admin.posts.category-input', ['post' => $alreadyPublishedPost])
                        @include('admin.posts.cost-input', ['post' => $alreadyPublishedPost])
                        @include('admin.posts.additional-inputs', ['post' => $alreadyPublishedPost, 'withoutCountry' => 1, 'wide' => 1])

                        @if ($alreadyPublishedPost)
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="update_imaged" value="1" id="update_imaged">
                                            <label class="form-check-label" for="update_imaged">Update Images <small>({{$alreadyPublishedPost->images()->count()}} images stored)</small></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12" style="font-size: 70%">
                            <a href="#" class="text-warning" data-toggle="modal" data-target="#some-posts-filled-automaticaly">
                                Some fields filled automaticaly when publishing from scraped post
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success min-w-100">Publish</button>
                    <button type="submit" class="btn btn-danger min-w-100 ask" name="cancel" value="1">Cancel</button>
                    <button type="submit" class="btn btn-warning min-w-100" name="skip" value="1">Skip</button>
                </div>
            </form>
        </div>
        <div class="col-sm-5">
            <div class="card" style="margin-bottom: 0px">
                <div class="card-header">
                    <h3 class="card-title">
                        Scraped Post #{{$scraperPost->id}} 
                        <a href="{{$scraperPost->url}}" target="_blank">source</a>
                    </h3>
                    <span class="{{$scraperPost->statusClass()}}" style="float: right">
                        {{$scraperPost->status->readable()}}
                    </span>
                </div>
                <div class="card-body" style="height:81vh;overflow-y: auto;">
                    <div class="row">
                        @foreach ($scraperPost->data as $field => $value)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        {{$field}}
                                        <i class="fas fa-fw fa-copy" style="cursor: pointer" data-copy="#field-{{$field}}"></i>
                                    </label>
                                    @if (is_array($value))
                                        <ul id="field-{{$field}}">
                                            @foreach ($value as $valueItem)
                                                @if (str_contains($field, 'images'))
                                                    <li class="scraped-images">
                                                        <a href="{{$valueItem}}" target="_blank">{{$valueItem}}</a>
                                                        <div>
                                                            <img src="{{$valueItem}}" alt="">
                                                        </div>
                                                    </li>
                                                @else
                                                    <li>{{$valueItem}}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p id="field-{{$field}}">{{$value}}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="some-posts-filled-automaticaly" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Automaticaly filled fields:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li><b>Slug</b>: calculated based on title</li>
                        <li><b>Meta Title</b>: calcualted in format "TITLE | on CATEGORY rigmangars.com"<br>if publish to existing post, meta updated only if title changed</li>
                        <li><b>Meta Description</b>: calcualted as first 140 chars of description and "| rigmangars.com" in the end</li>
                        <li><b>Group</b>: equipment</li>
                        <li><b>User</b>: taken from scraper</li>
                        <li><b>Country</b>: taken from user</li>
                        <li><b>Status</b>: approved</li>
                        <li><b>Original Lang</b>: en</li>
                        <li><b>Is Active</b>: Yes</li>
                        <li><b>Is urgent</b>: No</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="progress" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Progress</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul>
                        @foreach ($posts as $post)
                            <li style="{{$post->id == $scraperPost->id ? 'border:1px solid red' : ''}}">
                                {{$post->id}}:
                                <a href="{{route('admin.scraper-posts.publishing', $post)}}" class="{{$post->statusClass()}}">
                                    {{$post->status->readable()}}
                                </a>
                                <a href="{{$post->url}}" target="_blank">
                                    {{$post->url}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/scrapers.js')}}?v={{time()}}"></script>
    <script src="{{asset('/js/admin/post-category-selector.js')}}"></script>
@endpush
