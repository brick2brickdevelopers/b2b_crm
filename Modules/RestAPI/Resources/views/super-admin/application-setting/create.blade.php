<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('restapi::modules.applicationSettings.addApplication')</h4>
</div>

{!!  Form::open(['url' => '' ,'method' => 'post', 'id' => 'add-edit-form','class'=>'form-horizontal']) 	 !!}
<div class="modal-body">
    <div class="box-body">
        <div class="form-group">
            <label class="col-sm-2 control-label required" for="name">@lang('restapi::modules.applicationSettings.name')</label>
            <div class="col-sm-10">
                <input type="text" name="name" id="name" class="form-control">
                <div class="form-control-focus"> </div>
                <span class="help-block"></span>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button id="save" type="button" class="btn btn-custom">@lang('app.submit')</button>
</div>
{{ Form::close() }}
<script>
    $('#save').click(function () {
        $.easyAjax({
            url: '{{route('admin.application-setting.store')}}',
            container: '#add-edit-form',
            type: "POST",
            data: $('#add-edit-form').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    showSecret(response.applicationSetting.id);
                }
            }
        })
        return false;
    })
</script>

