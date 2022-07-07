<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.update') @lang('payroll::modules.payroll.salaryGroupComponents')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'editTicketType','class'=>'ajax-form','method'=>'PUT']) !!}
        <div class="form-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('payroll::modules.payroll.componentName')</label>
                        <input type="text" name="component_name" id="component_name" class="form-control" value="{{ $salaryComponent->component_name }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('payroll::modules.payroll.componentType')</label>
                        <select name="component_type" id="component_type" class="form-control">
                            <option @if ($salaryComponent->component_type == 'earning') selected @endif value="earning">@lang('payroll::modules.payroll.earning')</option>
                            <option @if ($salaryComponent->component_type == 'deduction') selected @endif value="deduction">@lang('payroll::modules.payroll.deduction')</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('payroll::modules.payroll.componentValue')</label>
                        <input type="text" name="component_value" id="component_value" class="form-control" value="{{ $salaryComponent->component_value }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('payroll::modules.payroll.valueType')</label>
                        <select name="value_type" id="value_type" class="form-control">
                            <option @if ($salaryComponent->value_type == 'fixed') selected @endif  value="fixed">@lang('payroll::modules.payroll.fixed')</option>
                            <option  @if ($salaryComponent->value_type == 'basic_pay_percent') selected @endif value="basic_pay_percent">@lang('payroll::modules.payroll.basicPayPercent')</option>
                        </select>
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

    $('#editTicketType').on('submit', function(e) {
        return false;
    })

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.salary-components.update', $salaryComponent->id)}}',
            container: '#editTicketType',
            type: "PUT",
            data: $('#editTicketType').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>