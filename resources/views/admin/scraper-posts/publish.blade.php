@extends('layouts.admin.app')

@section('title', 'Scrapers')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <div class="float-left">
                    <h1 class="m-0">Publishing of {{$posts->count()}} scraped Posts from run #{{$scraperRun->id}} of '{{$scraper->name}}' scraper</h1>
                </div>
                <div class="float-left pl-3">
                    <a href="{{route('admin.scraper-runs.show', $scraperRun)}}" class="btn btn-primary">
                        Back to #{{$scraperRun->id}} run
                    </a>
                    <a href="{{route('admin.scrapers.show', $scraper)}}" class="btn btn-primary">
                        Back to '{{$scraper->name}}' scraper
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-7">
            <form action="{{route('admin.scraper-posts.publish', $scraperPost)}}" method="post" class="card general-ajax-submit">
                @csrf
                <input type="hidden" name="created_post_id" value="{{$alreadyPublishedPost?->id}}">
                <div class="card-header">
                    <h3 class="card-title">
                        @if ($alreadyPublishedPost)
                            Published Post <a href="{{route('admin.posts.edit', $alreadyPublishedPost)}}" target="_blank">#{{$alreadyPublishedPost->id}}</a>
                        @else
                            New Post
                        @endif
                    </h3>
                </div>
                <div class="card-body">
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
                                            <input class="form-check-input" type="checkbox" name="update_imaged" value="1">
                                            <label class="form-check-label">Update Images</label>
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Scraped Post #{{$scraperPost->id}} <a href="{{$scraperPost->url}}" target="_blank">source</a></h3>
                </div>
                <div class="card-body" style="max-height: 95vh;overflow-y: auto;">
                    <div class="row">
                        @foreach ($scraperPost->data as $field => $value)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{$field}}</label>
                                    {{-- <input name="name" type="text" class="form-control"> --}}
                                    @if (is_array($value))
                                        <ul>
                                            @foreach ($value as $valueItem)
                                                <li>{{$valueItem}}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>{{$value}}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Progress</h3>
                </div>
                <div class="card-body">
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
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/scrapers.js')}}?v={{time()}}"></script>
    <script src="{{asset('/js/admin/post-category-selector.js')}}"></script>
@endpush
