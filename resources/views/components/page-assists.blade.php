
<div id="page-assist-importCreate" class="page-assist d-none">
    <div>
        <div class="pa-header">
            <b>@lang('ui.page-assists.importCreate.title')</b>
            <span class="pa-close">
                @svg('icons/close.svg')
            </span>
        </div>
        <div class="pa-content">@lang('ui.page-assists.importCreate.body')</div>
    </div>
</div>

<div id="page-assist-importValidationErrors" class="page-assist d-none">
    <div>
        <div class="pa-header">
            <b>@lang('ui.page-assists.importValidationErrors.title')</b>
            <span class="pa-close">
                @svg('icons/close.svg')
            </span>
        </div>
        <div class="pa-content">@lang('ui.page-assists.importValidationErrors.body')</div>
    </div>
</div>

<div id="page-assist-postCreate" class="page-assist d-none">
    <div>
        <div class="pa-header">
            <b>@lang('ui.page-assists.postCreate.title')</b>
            <span class="pa-close">
                @svg('icons/close.svg')
            </span>
        </div>
        <div class="pa-content">@lang('ui.page-assists.postCreate.body')</div>
    </div>
</div>

<div id="post-category-suggestion" class="page-assist d-none">
    <div>
        <div class="pa-header">
            <b>Suggestion: fields for <span class="cname"></span></b>
            <span class="pa-close">
                @svg('icons/close.svg')
            </span>
        </div>
        <div class="pa-content" style="padding:0">Please consider including following information into your post:
            <span class="cfields" title="click to copy" data-copy></span>
            This will help other users to understand your post.
        </div>
        <form
            action="{{route('feedbacks.store', 'report-category-fields')}}"
            method="post"
            class="general-ajax-submit with-recaptcha ask"
            data-asktitle="Are you sure?"
            data-asktext="We will make sure to review the issue you faced"
            data-askno="Cancel"
            data-askyes="Report suggestion"
        >
            @csrf
            <input type="hidden" name="data[category_id]" class="cid">
            <button type="submit" class="report-category-fields-suggestion">
                Report suggestion
            </button>
        </form>
    </div>
</div>