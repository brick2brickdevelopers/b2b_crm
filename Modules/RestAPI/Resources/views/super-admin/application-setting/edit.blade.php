<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('restapi::modules.applicationSettings.editApplication')</h4>
</div>

{!!  Form::open(['url' => '' ,'method' => 'PUT', 'id' => 'add-edit-form','class'=>'form-horizontal']) 	 !!}
<div class="modal-body">
    <div class="box-body">
        <div class="form-group">
            <label for="name">@lang('restapi::modules.applicationSettings.name')</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $applicationSetting->name }}">
            <div class="form-control-focus"> </div>
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <label>@lang('restapi::modules.applicationSettings.status')</label>
            <select name="status" id="status" class="form-control">
                <option @if($applicationSetting->status == 'enabled') selected @endif value="enabled">@lang('app.enable')</option>
                <option @if($applicationSetting->status == 'disabled') selected @endif value="disabled">@lang('app.disable')</option>
            </select>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button id="save" type="button" class="btn btn-custom">@lang('app.submit')</button>
</div>
{{ Form::close() }}
<script>
    $('#save').click(function () {
        var url = "{{ route('admin.application-setting.update', [$applicationSetting->id]) }}";
        $.easyAjax({
            url: url,
            container: '#add-edit-form',
            type: "POST",
            data: $('#add-edit-form').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })
</script>

