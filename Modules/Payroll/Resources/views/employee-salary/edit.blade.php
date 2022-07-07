<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('payroll::modules.payroll.updateSalary')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'editTicketType','class'=>'ajax-form','method'=>'PUT']) !!}
        <div class="form-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('app.employee')</label>
                        <h6>{{ ucwords($employee->name) }}</h6>
                        {!! Form::hidden('user_id', $employee->id) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('payroll::modules.payroll.grossEarning')</label>
                        <h6>{{ $global->currency->currency_symbol.$employeeSalary['netSalary'] }}</h6>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>@lang('app.amount')</label>
                        <input type="text" name="amount" id="amount" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>@lang('payroll::modules.payroll.valueType')</label>
                        <select name="type" id="type" class="form-control">
                            <option value="increment">@lang('payroll::modules.payroll.increment')</option>
                            <option value="decrement">@lang('payroll::modules.payroll.decrement')</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">@lang('app.date')</label>
                        <input type="text" name="date" id="start_date2" class="form-control" value="{{ \Carbon\Carbon::now()->timezone($global->timezene)->format($global->date_format) }}" autocomplete="off">
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

<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>

    $('#editTicketType').on('submit', function(e) {
        return false;
    })

    jQuery('#start_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    })

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.employee-salary.update', $employee->id)}}',
            container: '#editTicketType',
            type: "PUT",
            data: $('#editTicketType').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    loadTable();
                    $.unblockUI();
                    $('#ticketTypeModal').modal('hide');
                }
            }
        })
        
    });
</script>