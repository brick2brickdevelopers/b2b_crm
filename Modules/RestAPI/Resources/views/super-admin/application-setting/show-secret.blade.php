<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('restapi::modules.applicationSettings.applicationCredentials')</h4>
</div>

<div class="modal-body">
    <div class="form">
        <div class="keyTitle m-t-20 m-b-10">@lang('restapi::modules.applicationSettings.appKey')</div>
        <div class="palette-Grey">{{ $app_key }}</div>
        <div class="keyTitle m-t-20 m-b-10">@lang('restapi::modules.applicationSettings.appSecret')</div>
        <div class="palette-Grey" style="overflow-wrap: break-word;">{{ $app_secret }}</div>
        <div class="alert alert-info p-10 m-t-20"><strong>@lang('restapi::modules.applicationSettings.note')</strong>
            @lang('restapi::modules.applicationSettings.secretWarning')
        </div>
    </div>
</div>
<style>
    .palette-Grey{
        font-size: 20px;
        padding: 10px;
        background-color: #eee;
    }
    .keyTitle{
        font-size: 15px;
        font-weight: 400;
    }
</style>