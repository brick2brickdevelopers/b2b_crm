@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ $pageTitle }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.payroll.index') }}">{{ $pageTitle }}</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection


@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-inverse">
            <div class="panel-heading"> @lang('app.edit') @lang('payroll::modules.payroll.salarySlip')</div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    {!! Form::open(['id'=>'createEmployee','class'=>'ajax-form','method'=>'POST']) !!}
                    @method('PUT')
                    <div class="form-body">
                        <div class="row">
                            <div class="col-sm-12 m-b-20">
                                <h3 class="text-center">@lang('payroll::modules.payroll.salarySlipHeading') {{ $salarySlip->duration }} </h3>
                            </div>
                            <div class="col-xs-6 col-md-4  b-r"> <strong>@lang('modules.employees.fullName')</strong> <br>
                                <p class="text-muted">{{ ucwords($salarySlip->user->name) }}</p>
                            </div>
                            <div class="col-xs-6 col-md-4 b-r"> <strong>@lang('modules.employees.employeeId')</strong> <br>
                                <p class="text-muted">{{ $salarySlip->user->employeeDetail->employee_id }}</p>
                            </div>
                            <div class="col-md-4 col-xs-6 "> <strong>@lang('app.designation')</strong> <br>
                                <p class="text-muted">{{ (!is_null($salarySlip->user->employeeDetail->designation)) ? $salarySlip->user->employeeDetail->designation->name : '-' }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">

                            <div class="col-xs-6 col-md-4  b-r"> <strong>@lang('modules.employees.joiningDate')</strong> <br>
                                <p class="text-muted">{{ $salarySlip->user->employeeDetail->joining_date->format($global->date_format) }}</p>
                            </div>
                            <div class="col-xs-6 col-md-4"><strong>@lang('payroll::modules.payroll.salaryGroup')</strong> <br>
                                <p class="text-muted">{{ (!is_null($salarySlip->salary_group)) ? $salarySlip->salary_group->group_name : '-' }}</p>
                            </div>
                            
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">@lang('modules.payments.paidOn')</label>
                                    <input type="text" name="paid_on" id="start_date2" @if(!is_null($salarySlip->paid_on)) value="{{ $salarySlip->paid_on->format($global->date_format) }}" @endif class="form-control" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">@lang('payroll::modules.payroll.salaryPaymentMethod')</label>
                                    <select class="form-control" name="salary_payment_method_id" id="salary_payment_method_id" >
                                        <option value="">--</option>
                                        @foreach($salaryPaymentMethods as $item)
                                            <option 
                                            @if($item->id == $salarySlip->salary_payment_method_id) selected @endif
                                            value="{{ $item->id }}">{{ $item->payment_method }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">@lang('app.status')</label>
                                    <select class="form-control" name="status" id="status" >
                                        <option
                                        @if($salarySlip->status == 'generated') selected @endif 
                                        value="generated">{{ __('payroll::modules.payroll.generated') }}</option>
                                        <option @if($salarySlip->status == 'review') selected @endif 
                                        value="review">{{ __('payroll::modules.payroll.review') }}</option>
                                        <option @if($salarySlip->status == 'locked') selected @endif 
                                        value="locked">{{ __('payroll::modules.payroll.locked') }}</option>
                                        <option @if($salarySlip->status == 'paid') selected @endif 
                                        value="paid">{{ __('payroll::modules.payroll.paid') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">@lang('payroll::modules.payroll.expenseClaims')</label>
                                    <input type="text" name="expense_claims" id="expense_claims" class="form-control" value="{{ $salarySlip->expense_claims }}" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-borderless" id="earning-table">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-uppercase">@lang('payroll::modules.payroll.earning')</th>
                                                <th class="text-right text-uppercase">@lang('app.amount')</th>
                                                <th class="text-right text-uppercase">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>@lang('payroll::modules.payroll.basicPay')</td>
                                                <td class="text-right text-uppercase">
                                                    <input type="text" class="form-control" id="basic-salary" name="basic_salary" value="{{ $salarySlip->basic_salary }}">
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            @foreach ($earnings as $key=>$item)
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td class="text-right">
                                                        <input type="hidden" class="form-control" name="earnings_name[]" value="{{ $key }}">
                                                        <input type="text" class="form-control" name="earnings[]" value="{{ $item }}">
                                                    </td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                             
                                            @endforeach
                                            @foreach ($earningsExtra as $key=>$item)
                                                <tr>
                                                    <td><input type="text" class="form-control" name="extra_earnings_name[]" value="{{ $key }}"></td>
                                                    <td class="text-right">
                                                        <input type="number" min="0" step=".01" class="form-control" name="extra_earnings[]" value="{{ $item }}">
                                                    </td>
                                                    <td><button type="button" class="remove-field btn btn-danger"><i class="fa fa-times"></i></button></td>
                                                </tr>
                                             
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline btn-info" id="add-earning"><i class="fa fa-plus"></i> @lang('app.add')</button>
                            </div>
            
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-borderless" id="deduction-table">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-uppercase">@lang('payroll::modules.payroll.deduction')</th>
                                                <th class="text-right text-uppercase">@lang('app.amount')</th>
                                                <th class="text-right text-uppercase">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($deductions as $key=>$item)
                                            <tr>
                                                <td>{{ $key }}</td>
                                                <td class="text-right">
                                                    <input type="hidden" class="form-control" name="deductions_name[]" value="{{ $key }}">
                                                    <input type="text" class="form-control" name="deductions[]" value="{{ $item }}">
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        @endforeach

                                        @foreach ($deductionsExtra as $key=>$item)
                                            <tr>
                                                <td><input type="text" class="form-control" name="extra_deductions_name[]" value="{{ $key }}"></td>
                                                <td class="text-right">
                                                    <input type="number" min="0" step=".01" class="form-control" name="extra_deductions[]" value="{{ $item }}">
                                                </td>
                                                <td><button type="button" class="remove-field btn btn-danger"><i class="fa fa-times"></i></button></td>
                                            </tr>
                                        
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline btn-info" id="add-deduction"><i class="fa fa-plus"></i> @lang('app.add')</button>
                            </div>

                            <div class="col-sm-12 m-t-30">
                                <h3 class="text-center">
                                    <strong class="text-uppercase m-r-20">@lang('payroll::modules.payroll.netSalary'):</strong>
                                    {{ $global->currency->currency_symbol}}<span id="net-salary">{{$salarySlip->net_salary }}</span>
                                </h3>
                                <h5 class="text-center text-muted">@lang('payroll::modules.payroll.netSalary') = (@lang('payroll::modules.payroll.grossEarning') - @lang('payroll::modules.payroll.totalDeductions') + @lang('payroll::modules.payroll.reimbursement'))</h5>
                            </div>

                        </div>
                      
                    </div>
                    <div class="form-actions m-t-30">
                        <button type="submit" id="save-form" class="btn btn-success"><i
                                    class="fa fa-check"></i> @lang('app.save')</button>
                        <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>

    jQuery('#start_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    })

    $('#add-earning').click(function () {
        var earning = '<tr><td><input type="text" class="form-control" name="extra_earnings_name[]"></td><td><input type="number" min="0" value="0" step=".01" class="form-control" name="extra_earnings[]"></td><td><button type="button" class="remove-field btn btn-danger btn-outline"><i class="fa fa-times"></i></button></td></tr>';
        $('#earning-table tbody').append(earning);
    })

    $('#add-deduction').click(function () {
        var earning = '<tr><td><input type="text" class="form-control" name="extra_deductions_name[]"></td><td><input type="number" min="0" value="0" step=".01" class="form-control" name="extra_deductions[]"></td><td><button type="button" class="remove-field btn btn-danger btn-outline"><i class="fa fa-times"></i></button></td></tr>';
        $('#deduction-table tbody').append(earning);
    })

    $('body').on('click', '.remove-field', function () {
        $(this).closest('tr').remove();
    })

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.payroll.update', $salarySlip->id)}}',
            container: '#createEmployee',
            type: "POST",
            data: $('#createEmployee').serialize()
        })
    });

    $('body').on('keyup', "input[name='earnings[]'], input[name='deductions[]'], input[name='extra_earnings[]'],  input[name='extra_deductions[]'],  #basic-salary, #expense_claims", function () {
        calculateNetSalary();
    })

    function calculateNetSalary() {
        let grossEarning = $('#basic-salary').val();
        let totalDeductions = 0;
        let reimbursement = $('#expense_claims').val();
        reimbursement = parseFloat(reimbursement);

        $("input[name='earnings[]']").map(function(){
            let earning = $(this).val();
            grossEarning = parseFloat(grossEarning) + parseFloat(earning);
        }).get();

        $("input[name='deductions[]']").map(function(){
            let deductions = $(this).val();
            totalDeductions = parseFloat(totalDeductions) + parseFloat(deductions);
        }).get();

        $("input[name='extra_earnings[]']").map(function(){
            let earning = $(this).val();
            grossEarning = parseFloat(grossEarning) + parseFloat(earning);
        }).get();

        $("input[name='extra_deductions[]']").map(function(){
            let deductions = $(this).val();
            totalDeductions = parseFloat(totalDeductions) + parseFloat(deductions);
        }).get();
        
        let netSalary = (grossEarning - totalDeductions + reimbursement);
        $('#net-salary').html(netSalary.toFixed(2));
    }


</script>
@endpush