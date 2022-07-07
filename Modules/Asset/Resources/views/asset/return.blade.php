<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('asset::app.returnAsset')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'returnTo','class'=>'ajax-form','method'=>'PUT']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label class="control-label">@lang('asset::app.employee')</label>
                        <span class="help-block">{{ $history->user->name }}</span>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="control-label">@lang('asset::app.dateGiven')</label>
                            <span class="help-block">{{ $history->date_given->setTimezone($global->timezone)->format('d F Y H:i A') }} ({{ $history->date_given->setTimezone($global->timezone)->diffForHumans(\Carbon\Carbon::now()->setTimezone($global->timezone)) }})</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="control-label">@lang('asset::app.returnDate')</label>
                            <span class="help-block">{{ !is_null($history->return_date) ? ucwords($history->return_date->setTimezone($global->timezone)->format('d F Y H:i A')). ' ('.$history->return_date->setTimezone($global->timezone)->diffForHumans(\Carbon\Carbon::now()->setTimezone($global->timezone)) .')' : '-' }} </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="control-label required">@lang('asset::app.dateOfReturn')</label>
                            <input type="text" name="date_of_return" id="date_of_return" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="type" value="return">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label">@lang('asset::app.notes') </label>
                        <textarea class="textarea_editor form-control" rows="4" name="notes"
                                  id="notes">{{ $history->notes }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-group" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $("#employee_id").select2();

    jQuery('#date_of_return').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    });

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.history.update', [$history->asset_id, $history->id])}}',
            container: '#returnTo',
            type: "POST",
            data: $('#returnTo').serialize(),
            success: function (response) {
                if(response.status == 'success') {
                    $('#assetModal').modal('hide');
                    $('#right-sidebar-content').html(response.view);
                    window.LaravelDataTables["assets-table"].draw();
                }
            }
        })
    });
</script>