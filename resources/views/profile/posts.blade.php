@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.post.posts')</title>
	<meta name="description" content="@lang('meta.description.user.post.posts')">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    @if ($category)
        <x-bci :text="trans('ui.myPosts')" i="2" :href="route('profile.posts')" />
        @foreach ($category?->parents(true)??[] as $category)
            <x-bci
                :text="$category->name"
                :href="route('profile.posts', $category->parents(true))"
                :i="$loop->index + 3"
                :islast="$loop->last"
            />
        @endforeach
    @else
        <x-bci :text="trans('ui.myPosts')" i="2" islast="1" />
    @endif
@endsection

@section('style')
    <style>
        /* bulk actions block */
        .posts-header-block {
            justify-content: space-between;
        }
        .selected-block {
            margin-right: 10px;
            margin-top: 3px;
        }
        .selected-el {
            font-size: 85%;
            padding-left: 32px;
            margin-top: -10px;
        }
        .selected-el span{
            color: white;
        }
        .selected-all-el {
            align-items: center;
        }
        .header-actions .check-item {
            margin: 0px;
        }

        /* filters */
        .form-block {
            background: transparent;
            padding: 4px 50px 2px;
            margin: 0px;
            border-radius: 0px;
        }
        .sorting {
            margin: 0px;
        }
    </style>
@endsection

@section('content')
    <span class="hidden" data-categoryid="{{$category->id??null}}" page-data></span>
    <div class="main-block">
        <x-profile.nav active='posts' />
        <div class="content">
            <div class="row posts-header-block">
                <h1>
                    @lang('ui.myPosts')
                    (<span class="orange searched-amount">_</span>)
                </h1>
                <div class="row header-actions">
                    <div class="selected-block">
                        <div class="check-item">
                            <input type="checkbox" class="check-input" id="check-all" value="1">
                            <label for="check-all" class="check-label">Select All</label>
                        </div>
                        <div class="selected-el">
                            Selected:
                            <span class="selected-posts-count">0</span>
                        </div>
                    </div>
                    <div class="select-block">
                        <select class="styled apply-selected-action">
                            <option value="">Actions</option>
                            <option value="activete">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="trash">Trash</option>
                            <option value="recover">Recover</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="faq-item optionals" style="margin-bottom: 14px">
                <a href="" class="faq-top">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 255.99 511.99">
                        <path d="M253,248.62,18.37,3.29A10.67,10.67,0,1,0,3,18L230.56,256,3,494A10.67,10.67,0,0,0,18.37,508.7L253,263.37A10.7,10.7,0,0,0,253,248.62Z"/>
                    </svg>
                    <span class="text-show">Filters</span>
                </a>
                <div class="faq-hidden form-block">
                    <div class="row filter-block">
                        <x-profile.post-filters ttt="my-posts" />

                        <!--status-->
                        <div class="col-6">
                            <label class="label">@lang('ui.status')</label>
                            <div class="select-block">
                                <select class="styled" name="status">
                                    <option value="">@lang('ui.notSpecified')</option>
                                    <option value="active" @selected(request()->status == 'active')>Active</option>
                                    <option value="hidden" @selected(request()->status == 'hidden')>Hidden</option>
                                    <option value="pending" @selected(request()->status == 'pending')>Pending</option>
                                    <option value="rejected" @selected(request()->status == 'rejected')>Rejected</option>
                                    <option value="trashed" @selected(request()->status == 'trashed')>Trashed</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="searched-categories-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="searched-content"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/posts-filter.js')}}"></script>
    <script src="{{asset('js/posts.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.trigger-show-filters').click(function(e) {
                e.preventDefault();
                let wraper = $('.filters-dropdown');
                if (wraper.hasClass('active')) {
                    $('.filter-block').slideUp();
                } else {
                    $('.filter-block').slideDown();
                }
                wraper.toggleClass('active');
            })
        });
    </script>
@endsection
