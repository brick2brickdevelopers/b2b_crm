@extends('layouts.member-app')

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
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
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

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">@lang('app.select') @lang('app.month')</label>
                                <select class="select2 form-control" data-placeholder="" id="month">
                                    <option value="0">--</option>
                                    <option value="01">@lang('app.january')</option>
                                    <option value="02">@lang('app.february')</option>
                                    <option value="03">@lang('app.march')</option>
                                    <option value="04">@lang('app.april')</option>
                                    <option value="05">@lang('app.may')</option>
                                    <option value="06">@lang('app.june')</option>
                                    <option value="07">@lang('app.july')</option>
                                    <option value="08">@lang('app.august')</option>
                                    <option value="09">@lang('app.september')</option>
                                    <option value="10">@lang('app.october')</option>
                                    <option value="11">@lang('app.november')</option>
                                    <option value="12">@lang('app.december')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">@lang('app.select') @lang('app.year')</label>
                                <select class="select2 form-control" data-placeholder="" id="year">
                                    @for($i = $year; $i >= ($year-4); $i--)
                                        <option @if($i == $year) selected @endif value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                    </form>
                </div>



                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                        <thead>
                        <tr>
                            <th>@lang('app.month')</th>
                            <th>@lang('payroll::modules.payroll.netSalary')</th>
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

    function loadTable(){

        var month    = $('#month').val();
        var year     = $('#year').val();

        table = $('#users-table').dataTable({
            "lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            responsive: true,
            processing: true,
            serverSide: true,
            destroy: true,
            stateSave: true,
            ajax: '{!! route('member.payroll.data') !!}?month=' + month + '&year=' + year,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function( oSettings ) {
                let recordsCount = oSettings._iRecordsTotal;
            },
            columns: [
                { data: 'month', name: 'month' },
                { data: 'net_salary', name: 'net_salary' },
                { data: 'paid_on', name: 'paid_on' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' },
            ]
        });
    }

    $('#month, #year').change(function () {
        loadTable();
    });

    $('body').on('click', '.show-salary-slip', function () {
        $(".right-sidebar").slideDown(50).addClass("shw-rside");

        var id = $(this).data('salary-slip-id');
        var url = "{{ route('member.payroll.show',':id') }}";
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

    loadTable();

</script>
@endpush