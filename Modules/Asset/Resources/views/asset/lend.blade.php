<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('asset::app.lendAsset')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'lendTo','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label class="required">@lang('asset::app.employee')</label>
                        <select class="select2 form-control" name="employee_id" id="employee_id" data-style="form-control">
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="control-label required">@lang('asset::app.dateGiven')</label>
                            <input type="text" name="date_given" id="date_given" class="form-control"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="control-label">@lang('asset::app.returnDate')</label>
                            <input type="text" name="return_date" id="return_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label">@lang('asset::app.notes') </label>
                        <textarea class="textarea_editor form-control" rows="4" name="notes"
                                  id="notes"></textarea>
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
    // $('#addContractType').on('submit', function(e) {
    //     return false;
    // })

    jQuery('#date_given, #return_date').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    });

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.history.store', $asset->id)}}',
            container: '#lendTo',
            type: "POST",
            data: $('#lendTo').serialize(),
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