<form
    action="{{route('subscriptions.cancel')}}"
    method="POST"
    class="general-ajax-submit show-full-loader ask {{$class??''}}"
    data-asktitle="@lang('ui.planCancelModalTitle')"
    data-asktext="@lang('ui.planCancelModalText')"
    data-askyes="@lang('ui.planCancelModalYes')"
    data-askno="@lang('ui.planCancelModalNo')"
>
    @csrf
    <button type="submit" class="{{$btnclass??''}}">{{$btntext??'Cancel'}}</button>
</form>
