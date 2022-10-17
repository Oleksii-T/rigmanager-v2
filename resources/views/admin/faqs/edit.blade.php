@extends('layouts.admin.app')

@section('title', 'Edit FAQ')

@section('content_header')
    <x-admin.title
        text="Edit FAQ"
    />
@stop

@section('content')
    <form action="{{route('admin.faqs.update', $faq)}}" method="POST" class="general-ajax-submit">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Question</label>
                            <x-admin.multi-lang-input name="question" :model="$faq" />
                            <span data-input="question" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Answer</label>
                            <x-admin.multi-lang-input name="answer" textarea="1" :model="$faq" />
                            <span data-input="answer" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <input name="slug" type="text" class="form-control" value="{{$faq->slug}}">
                            <span data-input="slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Order</label>
                            <input name="order" type="text" class="form-control" value="{{$faq->order}}">
                            <span data-input="order" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{route('admin.faqs.index')}}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
