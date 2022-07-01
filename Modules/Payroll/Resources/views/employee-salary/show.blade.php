<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('payroll::modules.payroll.salaryHistory')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        <div class="col-md-12">
            <h4>{{ ucwords($employee->name) }}</h4>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('app.amount')</th>
                    <th>@lang('payroll::modules.payroll.valueType')</th>
                    <th>@lang('app.date')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($salaryHistory as $key=>$salary)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>
                            @if($salary->type == 'initial')
                                {{ $global->currency->currency_symbol.$salary->amount }}
                            @elseif($salary->type == 'increment')
                                <span class="text-success">+{{ $global->currency->currency_symbol.$salary->amount }}</span>
                            @elseif($salary->type == 'decrement')
                                <span class="text-danger">-{{ $global->currency->currency_symbol.$salary->amount }}</span>
                            @endif
                        </td>
                        <td>
                            {{ ucwords($salary->type) }}
                        </td>
                        <td>
                           {{ $salary->date->format($global->date_format) }}
                        </td>
                        <td>
                            <a href="javascript:;" data-salary-id="{{ $salary->id }}"
                               class="btn btn-sm btn-danger btn-rounded btn-outline sa-params"><i
                                        class="fa fa-times"></i> @lang('app.remove')</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>
                            @lang('messages.noRecordFound')
                        </td>
                    </tr>
                @endforelse
                    <tr>
                        <td class="font-bold">@lang('app.total')</td>
                        <td class="font-bold">
                            {{ $global->currency->currency_symbol.$employeeSalary['netSalary'] }}
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>

    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('salary-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted salary!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.employee-salary.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            loadTable();
                            $.unblockUI();
                            $('#ticketTypeModal').modal('hide');
                        }
                    }
                });
            }
        });
    });
</script>