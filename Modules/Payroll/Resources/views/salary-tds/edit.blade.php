<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.update') @lang('payroll::app.menu.salaryTds')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'editTicketType','class'=>'ajax-form','method'=>'PUT']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('payroll::modules.payroll.salaryFrom')</label>
                        <input type="text" name="salary_from" id="salary_from" value="{{ $salaryTds->salary_from }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('payroll::modules.payroll.salaryTo')</label>
                        <input type="text" name="salary_to" id="salary_to" class="form-control" value="{{ $salaryTds->salary_to }}" >
                    </div>
                    <div class="form-group">
                        <label>@lang('payroll::modules.payroll.salaryPercent')</label>
                        <input type="text" name="salary_percent" id="salary_percent" class="form-control" value="{{ $salaryTds->salary_percent }}" >
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
            url: '{{route('admin.salary-tds.update', $salaryTds->id)}}',
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