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
                <li><a href="{{ route('admin.settings.index') }}">@lang('app.menu.settings')</a></li>
                <li class="active">{{ $pageTitle }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('payroll::app.menu.salaryTds')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('payroll::sections.payroll_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="white-box">
                                        <h3>@lang('app.addNew') @lang('payroll::app.menu.salaryTds')</h3>

                                        {!! Form::open(['id'=>'createTypes','class'=>'ajax-form','method'=>'POST']) !!}

                                        <div class="form-body row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('payroll::modules.payroll.salaryFrom') ({{ $global->currency->currency_symbol . ' ' . $global->currency->currency_code }})</label>
                                                    <input type="text" name="salary_from" id="salary_from" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('payroll::modules.payroll.salaryTo') ({{ $global->currency->currency_symbol . ' ' . $global->currency->currency_code }})</label>
                                                    <input type="text" name="salary_to" id="salary_to" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('payroll::modules.payroll.salaryPercent')</label>
                                                    <input type="text" name="salary_percent" id="salary_percent" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-actions">
                                                    <button type="submit" id="save-type" class="btn btn-success"><i
                                                                class="fa fa-check"></i> @lang('app.save')
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        {!! Form::close() !!}

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="white-box">
                                        <h3>@lang('payroll::app.menu.salaryTds')</h3>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>@lang('payroll::modules.payroll.assignTdsSalary') ({{ $global->currency->currency_symbol . ' ' . $global->currency->currency_code }})</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="tds_salary" id="tds_salary" value="{{ $payrollSetting->tds_salary }}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label>@lang('payroll::modules.payroll.financeStartMonth')</label>
                                                <div class="form-group">
                                                    <select class="form-control" name="finance_month" id="finance_month">
                                                        <option @if($payrollSetting->finance_month == '01') selected @endif value="01">@lang('app.january')</option>
                                                        <option @if($payrollSetting->finance_month == '02') selected @endif value="02">@lang('app.february')</option>
                                                        <option @if($payrollSetting->finance_month == '03') selected @endif value="03">@lang('app.march')</option>
                                                        <option @if($payrollSetting->finance_month == '04') selected @endif value="04">@lang('app.april')</option>
                                                        <option @if($payrollSetting->finance_month == '05') selected @endif value="05">@lang('app.may')</option>
                                                        <option @if($payrollSetting->finance_month == '06') selected @endif value="06">@lang('app.june')</option>
                                                        <option @if($payrollSetting->finance_month == '07') selected @endif value="07">@lang('app.july')</option>
                                                        <option @if($payrollSetting->finance_month == '08') selected @endif value="08">@lang('app.august')</option>
                                                        <option @if($payrollSetting->finance_month == '09') selected @endif value="09">@lang('app.september')</option>
                                                        <option @if($payrollSetting->finance_month == '10') selected @endif value="10">@lang('app.october')</option>
                                                        <option @if($payrollSetting->finance_month == '11') selected @endif value="11">@lang('app.november')</option>
                                                        <option @if($payrollSetting->finance_month == '12') selected @endif value="12">@lang('app.december')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2 text-center">
                                                <label>@lang('app.status')</label>
                                                <div class="form-group">
                                                    <span class="switchery-demo">
                                                        <input type="checkbox" id="tds-status" name="status"
                                                                    @if($payrollSetting->tds_status) checked
                                                                    @endif class="js-switch " data-color="#00c292"
                                                                    data-secondary-color="#f96262"/>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2 m-t-25">
                                                <button type="button" class="btn btn-success" id="save-settings"><i class="fa fa-check"></i> @lang('app.save')</button>
                                            </div>
                                        </div>


                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('payroll::modules.payroll.salaryFrom')</th>
                                                    <th>@lang('payroll::modules.payroll.salaryTo')</th>
                                                    <th>@lang('payroll::modules.payroll.salaryPercent')</th>
                                                    <th>@lang('app.action')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($salaryTds as $key=>$tds)
                                                    <tr>
                                                        <td>{{ ($key+1) }}</td>
                                                        <td>{{ $global->currency->currency_symbol.$tds->salary_from }}</td>
                                                        <td>{{ $global->currency->currency_symbol.$tds->salary_to }}</td>
                                                        <td>{{ $tds->salary_percent }}%</td>
                                                        <td>
                                                            <a href="javascript:;" data-type-id="{{ $tds->id }}"
                                                               class="btn btn-sm btn-info btn-rounded btn-outline edit-type"><i
                                                                        class="fa fa-edit"></i> @lang('app.edit')</a>
                                                            <a href="javascript:;" data-type-id="{{ $tds->id }}"
                                                               class="btn btn-sm btn-danger btn-rounded btn-outline delete-type"><i
                                                                        class="fa fa-times"></i> @lang('app.remove')</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">
                                                            @lang('messages.noRecordFound')
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="ticketTypeModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

<script type="text/javascript">
    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function () {
        new Switchery($(this)[0], $(this).data());

    });

    $('#save-settings').click(function () {

        if ($('#tds-status').is(':checked'))
            var status = 1;
        else
            var status = 0;

        var tdsSalary = $('#tds_salary').val();
        var finance_month = $('#finance_month').val();

        var url = '{{route('admin.salary-tds.status')}}';
        $.easyAjax({
            url: url,
            type: "POST",
            data: {'status': status, 'tdsSalary': tdsSalary, 'finance_month': finance_month, '_method': 'POST', '_token': '{{ csrf_token() }}'},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });


    //    save project members
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.salary-tds.store')}}',
            container: '#createTypes',
            type: "POST",
            data: $('#createTypes').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });


    $('body').on('click', '.delete-type', function () {
        var id = $(this).data('type-id');
        swal({
            title: "Are you sure?",
            text: "This will remove the salary TDS from the list.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.salary-tds.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });


    $('.edit-type').click(function () {
        var typeId = $(this).data('type-id');
        var url = '{{ route("admin.salary-tds.edit", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.tickets.ticketType') }}");
        $.ajaxModal('#ticketTypeModal', url);
    })


</script>


@endpush

