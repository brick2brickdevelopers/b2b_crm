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
                <li class="active">{{ $pageTitle }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">

<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<style>
    .table-responsive .table-custom-border {
        border-left: 1px solid #e4e7ea !important;
        border-right: 1px solid #e4e7ea !important;
    }
    #regenerate-buttons, #payment-fields {
        display: none;
    }

    .select2-container-multi .select2-choices .select2-search-choice {
        background: #ffffff !important;
    }
</style>
@endpush

@section('content')

    <div class="row">

        <div class="col-md-12">
            <div class="white-box">
                <div class="row payroll::modules.payroll.generated-b-15">
                    <div class="col-md-12">
                        <h4>@lang('payroll::app.menu.payroll') </h4>
                    </div>
                    <form action="" id="filter-form">
                        

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">@lang('app.select') @lang('app.year')</label>
                                <select class="select2 form-control" data-placeholder="" id="year">
                                    @for($i = $year; $i >= ($year-4); $i--)
                                        <option @if($i == $year) selected @endif value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">@lang('payroll::app.menu.payroll') @lang('payroll::app.menu.cycle')</label>
                                <select class="select2 form-control" data-placeholder="" id="payrollCycle" name="payroll_cycle">
                                    @foreach($payrollCycles as $payrollCycle)
                                        <option value="{{ $payrollCycle->id }}"> {{ ucwords($payrollCycle->cycle) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">@lang('app.select') @lang('app.month')</label>
                                <select class="select2 form-control" data-placeholder="" id="month">

                                </select>
                            </div>
                        </div>
                        
                    </form>
                </div>
                
                <div class="row" id="ticket-filters">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="checkbox checkbox-info">
                                <input id="mark_leaves_paid" name="mark_leaves_paid" checked type="checkbox">
                                <label for="mark_leaves_paid">@lang('payroll::modules.payroll.markApprovedLeavesPaid')</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="checkbox checkbox-info  ">
                                <input id="mark_absent_unpaid" name="mark_absent_unpaid" type="checkbox">
                                <label for="mark_absent_unpaid">@lang('payroll::modules.payroll.markAbsentUnpaid')</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="checkbox checkbox-info">
                                <input id="useAttendance" name="useAttendance" checked type="checkbox">
                                <label for="useAttendance">@lang('payroll::modules.payroll.useAttendance')</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="checkbox checkbox-info  ">
                                <input id="includeExpenseClaims" name="includeExpenseClaims" checked type="checkbox">
                                <label for="includeExpenseClaims">@lang('payroll::modules.payroll.includeExpenseClaims')</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-info  ">
                                <input id="addTimelogs" name="addTimelogs" type="checkbox">
                                <label for="addTimelogs">@lang('payroll::modules.payroll.addTimelogs')</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" id="generate-buttons">
                        <div class="form-group">
                            <button type="button" id="generate-payslip" class="btn btn-success"><i class="fa fa-send"></i> @lang('payroll::modules.payroll.generate') </button>
                        </div>
                    </div>
                    <div class="col-md-12" id="regenerate-buttons">
                        <div class="form-group">
                            <button type="button" id="regenerate-payslip" class="btn btn-success"><i class="fa fa-send"></i> @lang('payroll::modules.payroll.regenerate') </button>
                            <button type="button" id="show-status-modal" class="btn btn-info"><i class="fa fa-tag"></i> @lang('app.status') </button>
                        </div>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" ></th>
                            <th>@lang('app.name')</th>
                            <th>@lang('payroll::modules.payroll.netSalary')</th>
                            <th>@lang('payroll::app.duration')</th>
                            <th>@lang('modules.payments.paidOn')</th>
                            <th>@lang('app.status')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="status-modal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">@lang('app.status')</h4>
                </div>
                <div class="modal-body">
                    <div class="row p-b-5">
                        <div class="col-sm-4">
                            <div class="radio radio-inverse">
                                <input type="radio" name="status" checked id="status_generated" value="generated">
                                <label for="status_generated" class="text-inverse">@lang('payroll::modules.payroll.generated')</label>
                            </div>
                        </div>
                        <div class="col-sm-8">@lang('payroll::modules.payroll.generatedInfo')</div>
                    </div>
                    <div class="row p-t-5 p-b-5">
                        <div class="col-sm-4">
                            <div class="radio radio-info">
                                <input type="radio" name="status" id="status_review" value="review">
                                <label for="status_review" class="text-info">@lang('payroll::modules.payroll.review')</label>
                            </div>
                        </div>
                        <div class="col-sm-8">@lang('payroll::modules.payroll.reviewInfo')</div>
                    </div>
                    <div class="row p-t-5 p-b-5">
                        <div class="col-sm-4">
                            <div class="radio radio-danger">
                                <input type="radio" name="status" id="status_locked" value="locked">
                                <label for="status_locked" class="text-danger">@lang('payroll::modules.payroll.locked')</label>
                            </div>
                        </div>
                        <div class="col-sm-8">@lang('payroll::modules.payroll.lockedInfo')</div>
                    </div>
                    <div class="row p-t-5 p-b-5">
                        <div class="col-sm-4">
                            <div class="radio radio-success">
                                <input type="radio" name="status" id="status_paid" value="paid">
                                <label for="status_paid" class="text-success">@lang('payroll::modules.payroll.paid')</label>
                            </div>
                        </div>
                        <div class="col-sm-8">@lang('payroll::modules.payroll.paidInfo')</div>
                    </div>
                    <div class="row p-t-5 p-b-5" id="payment-fields">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">@lang('modules.payments.paidOn')</label>
                                <input type="text" value="{{ \Carbon\Carbon::now()->timezone($global->timezone)->format($global->date_format) }}" name="paid_on" id="paid_on" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">@lang('payroll::modules.payroll.salaryPaymentMethod')</label>
                                <select class="form-control" name="salary_payment_method_id" id="salary_payment_method_id" >
                                    @foreach($salaryPaymentMethods as $item)
                                        <option 
                                        value="{{ $item->id }}">{{ $item->payment_method }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">@lang('payroll::modules.payroll.addAmountExpenses')</label>
                                <select class="form-control" name="add_expenses" id="add_expenses" >
                                    <option value="no">@lang('app.no')</option>
                                    <option value="yes">@lang('app.yes')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="update-status" class="btn btn-success">@lang('app.save')</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>

<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script>
    jQuery('#paid_on').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    })

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    var table;

    $(function() {
        // loadTable();

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('salary-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted payroll!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.payroll.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                loadTable();
                            }
                        }
                    });
                }
            });
        });

        $( document ).ready(function() {
            getCycleData();
        });

        $('body').on('change', '#payrollCycle', function(){
            getCycleData();
        });

        function getCycleData(){
            var payrollCycle = $('#payrollCycle').val();
            var year = $('#year').val();
            var token = "{{ csrf_token() }}";
            $.easyAjax({
                url: '{{route('admin.payroll.get-cycle-data')}}',
                type: "POST",
                data: {
                    payrollCycle: payrollCycle,
                    year: year,
                    with_view: 'yes',
                    _token : token
                },
                success: function (response) {
                    $.unblockUI();
                    $('#month').html(response.view);
                    $('#month').select2();

                    loadTable();

                }
            })
        }


        $('body').on('click', '#generate-payslip', function(){
            var month = $('#month').val();
            var year  = $('#year').val();
            var cycle  = $('#payrollCycle').val();
            var token = "{{ csrf_token() }}";
            var payroll_cycle = $('#payrollCycle').val();

            if ($('#mark_leaves_paid').is(':checked')) {
                var markLeavesPaid = '1';
            } else {
                var markLeavesPaid = '0';
            }

            if ($('#mark_absent_unpaid').is(':checked')) {
                var markAbsentUnpaid = '1';
            } else {
                var markAbsentUnpaid = '0';
            }

            if ($('#useAttendance').is(':checked')) {
                var useAttendance = '1';
            } else {
                var useAttendance = '0';
            }

            if ($('#includeExpenseClaims').is(':checked')) {
                var includeExpenseClaims = '1';
            } else {
                var includeExpenseClaims = '0';
            }

            if ($('#addTimelogs').is(':checked')) {
                var addTimelogs = '1';
            } else {
                var addTimelogs = '0';
            }

            $.easyAjax({
                url: '{{route('admin.payroll.generatePaySlip')}}',
                type: "POST",
                data: {
                    month: month,
                    year: year,
                    markLeavesPaid: markLeavesPaid,
                    markAbsentUnpaid: markAbsentUnpaid,
                    useAttendance: useAttendance,
                    includeExpenseClaims: includeExpenseClaims,
                    addTimelogs: addTimelogs,
                    payroll_cycle: payroll_cycle,
                    cycle: cycle,
                    _token : token
                },
                success: function (response) {
                    if(response.status == "success"){
                        $.unblockUI();
                        loadTable();
                    }
                }
            })

        });
        
        $('body').on('click', '#regenerate-payslip', function(){
            var month = $('#month').val();
            var year  = $('#year').val();
            var cycle  = $('#payrollCycle').val();
            var token = "{{ csrf_token() }}";
            
            if ($('#mark_leaves_paid').is(':checked')) {
                var markLeavesPaid = '1';
            } else {
                var markLeavesPaid = '0';
            }
            
            if ($('#mark_absent_unpaid').is(':checked')) {
                var markAbsentUnpaid = '1';
            } else {
                var markAbsentUnpaid = '0';
            }
            
            if ($('#useAttendance').is(':checked')) {
                var useAttendance = '1';
            } else {
                var useAttendance = '0';
            }
            
            if ($('#includeExpenseClaims').is(':checked')) {
                var includeExpenseClaims = '1';
            } else {
                var includeExpenseClaims = '0';
            }

            if ($('#addTimelogs').is(':checked')) {
                var addTimelogs = '1';
            } else {
                var addTimelogs = '0';
            }

            var userIds = $("input[name='salary_ids[]']")
              .map(function(){
                    if($(this).is(':checked')) {
                        return $(this).data('user-id');
                    }
                }).get();

            $.easyAjax({
                url: '{{route('admin.payroll.generatePaySlip')}}',
                type: "POST",
                data: {
                    month: month,
                    year: year,
                    markLeavesPaid: markLeavesPaid,
                    markAbsentUnpaid: markAbsentUnpaid,
                    useAttendance: useAttendance,
                    includeExpenseClaims: includeExpenseClaims,
                    userIds: userIds,
                    cycle: cycle,
                    addTimelogs: addTimelogs,
                   _token : token
                },
                success: function (response) {
                    if(response.status == "success"){
                        $.unblockUI();
                        loadTable();
                    }
                }
            })

        });

    });
    function loadTable(){

        var month    = $('#month').val();
        var year     = $('#year').val();
        var payrollCycle     = $('#payrollCycle').val();

        table = $('#users-table').dataTable({
            "lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            responsive: true,
            processing: true,
            serverSide: true,
            destroy: true,
            stateSave: true,
            ajax: '{!! route('admin.payroll.data') !!}?month=' + month + '&year=' + year + '&payrollCycle=' + payrollCycle,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function( oSettings ) {
                let recordsCount = oSettings._iRecordsTotal;

                if (recordsCount > 0) {
                    $('.table-responsive').show();
                    // $('#ticket-filters').hide();
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                } else {
                    $('.table-responsive').hide();
                    // $('#ticket-filters').show();
                }
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'net_salary', name: 'net_salary' },
                { data: 'salary_from', name: 'salary_from' },
                { data: 'paid_on', name: 'paid_on' },
                { data: 'salary_status', name: 'salary_status' },
                { data: 'action', name: 'action' },
            ]
        });
    }

    $('#month, #year, #payrollCycle').change(function () {
        loadTable();
    });

    $('body').on('click', '.show-salary-slip', function () {
        $(".right-sidebar").slideDown(50).addClass("shw-rside");

        var id = $(this).data('salary-slip-id');
        var url = "{{ route('admin.payroll.show',':id') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status == "success") {
                    $('#right-sidebar-content').html(response.view);
                }
            }
        });
    });

    $('#select-all').click(function () {

        if($(this).is(':checked')) {
            $("input[name='salary_ids[]']").map(function(){
                return $(this).prop("checked", true);
            });
        } else {
            $("input[name='salary_ids[]']").map(function(){
                return $(this).prop("checked", false) ;
            });
        }
        var total=$('input[name="salary_ids[]"]:checked').length;
        togglegenerateOptions(total);
        
    })

    $("body").on('click', "input[name='salary_ids[]']", function () {
        var total=$('input[name="salary_ids[]"]:checked').length;
        togglegenerateOptions(total);        
    });

    function togglegenerateOptions(totalChecked) {
        if (totalChecked > 0) {
            $('#regenerate-buttons').show();
            $('#generate-buttons').hide();
        } else {
            $('#regenerate-buttons').hide();
            $('#generate-buttons').show();
        }
    }

    $('#show-status-modal').click(function(){
        $('#status-modal').modal('show');
    })

    $("input[name='status']").change(function () {
        let value = $(this).val();
        if (value == "paid") {
            $('#payment-fields').show();
        } else {
            $('#payment-fields').hide();
        }
    })

    $('#update-status').click(function () {
        let status = $("input[name='status']:checked").val();
        let paidOn = $("#paid_on").val();
        let paymentMethod = $("#salary_payment_method_id").val();
        let addExpenses = $("#add_expenses").val();

        var token = "{{ csrf_token() }}";
        var salaryIds = $("input[name='salary_ids[]']")
              .map(function(){
                    if($(this).is(':checked')) {
                        return $(this).val();
                    }
                }).get();
        
        var url = "{{ route('admin.payroll.updateStatus') }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, salaryIds: salaryIds, status: status, paymentMethod: paymentMethod, paidOn: paidOn, add_expenses: addExpenses},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    loadTable();
                    $('#status-modal').modal('hide');
                }
            }
        });
    });


</script>
@endpush