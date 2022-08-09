@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right bg-title-right">
            {{-- <a href="{{ route('admin.estimates.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.estimates.createEstimate') <i class="fa fa-plus" aria-hidden="true"></i></a> --}}

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterange-picker/daterangepicker.css') }}" />
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="white-box">


            @section('filter-section')
                <div class="row" id="ticket-filters">

                    <form action="" id="filter-form">
                        <div class="col-xs-12">
                            <h5>@lang('app.selectDateRange')</h5>
                            <div class="form-group">
                                <div id="reportrange" class="form-control reportrange">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down pull-right"></i>
                                </div>

                                <input type="hidden" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                                    value="" />
                                <input type="hidden" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                                    value="" />
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="">Campaign Status Filter</label>
                                <select class="form-control" name="campaignStatus" id="campaignStatus"
                                    data-style="form-control">
                                    <option value="">All</option>
                                    <option value="1">Available</option>
                                    <option value="2">Completed</option>
                                    <option value="3">Follow</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="">ChooseAgents</label>
                                <select class="form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" id="agent_id"
                                    name="agent_id">
                                    <option value="">@lang('modules.lead.all')</option>
                                    @foreach ($leadAgents as $emp)
                                        <option value="{{ $emp->id }}">{{ ucwords($emp->user->name) }} @if ($emp->user->id == $user->id)
                                                (YOU)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Call Outcome</label>
                                <select class="form-control" name="callOutcome" id="callOutcome" data-style="form-control">
                                    <option value="">All</option>

                                    @foreach ($callOutcomes as $callOutcome)
                                        <option value="{{ $callOutcome->id }}">{{ $callOutcome->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-xs-12">&nbsp;</label>
                                <button type="button" id="apply-filters" class="btn btn-success col-md-6"><i
                                        class="fa fa-check"></i> @lang('app.apply')</button>
                                <button type="button" id="reset-filters"
                                    class="btn btn-inverse col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i>
                                    @lang('app.reset')</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endsection

            <div class="table-responsive">
                {!! $html->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
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
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@if ($global->locale == 'en')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.{{ $global->locale }}-AU.min.js">
    </script>
@else
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.{{ $global->locale }}.min.js">
    </script>
@endif
<script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/daterange-picker/daterangepicker.js') }}"></script>




{!! $html->scripts() !!}

<script>
    $(function() {
        var dateformat = '{{ $global->moment_format }}';

        var start = '';
        var end = '';

        function cb(start, end) {
            if (start) {
                $('#start-date').val(start.format(dateformat));
                $('#end-date').val(end.format(dateformat));
                $('#reportrange span').html(start.format(dateformat) + ' - ' + end.format(dateformat));
            }

        }
        moment.locale('{{ $global->locale }}');
        $('#reportrange').daterangepicker({
            // startDate: start,
            // endDate: end,
            locale: {
                language: '{{ $global->locale }}',
                format: '{{ $global->moment_format }}',
            },
            linkedCalendars: false,
            ranges: dateRangePickerCustom
        }, cb);

        cb(start, end);

    });
    var table;
    $(function() {
        jQuery('#date-range').datepicker({
            toggleActive: true,
            language: '{{ $global->locale }}',
            autoclose: true,
            weekStart: '{{ $global->week_start }}',
            format: '{{ $global->date_picker_format }}',
        });

        $('#tab-table').on('preXhr.dt', function(e, settings, data) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            var status = $('#status').val();

            var campaignStatus = $('#campaignStatus').val();
            var callPuspose = $('#callPuspose').val();
            var callOutcome = $('#callOutcome').val();
            var agent_id = $('#agent_id').val();
            data['start_date'] = startDate;
            data['end_date'] = endDate;
            data['campaignStatus'] = campaignStatus;
            data['callOutcome'] =  callOutcome;
            data['agent_id'] = agent_id;
        });

        loadTable();

        $('body').on('click', '.change-status', function() {
            var id = $(this).data('estimate-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.estimateCancelText')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.confirmCancel')",
                cancelButtonText: "@lang('messages.confirmNo')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('admin.estimates.change-status', ':id') }}";
                    url = url.replace(':id', id);

                    $.easyAjax({
                        type: 'GET',
                        url: url,
                        success: function(response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                window.LaravelDataTables["estimates-table"].draw();
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click', '.sa-params', function() {
            var id = $(this).data('estimate-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.confirmation.deleteEstimate')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.deleteConfirmation')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('admin.estimates.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                loadTable();
                            }
                        }
                    });
                }
            });
        });

    });

    $('body').on('click', '.sa-params-check', function() {
        swal({
            title: "@lang('messages.estimateDeleteCheck')",
        });
    });

    function loadTable() {
        window.LaravelDataTables["tab-table"].draw();
    }

    $('.toggle-filter').click(function() {
        $('#ticket-filters').toggle('slide');
    })

    $('#apply-filters').click(function() {
        loadTable();
    });

    $('#reset-filters').click(function() {
        $('#start-date').val(null);
        $('#end-date').val(null);
        $('#filter-form')[0].reset();
        $('#reportrange span').html('');
        loadTable();
    })

    $('body').on('click', '.sendButton', function() {
        // var id = $(this).data('estimate-id');
        // var url = "{{ route('admin.estimates.send-estimate', ':id') }}";
        url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {
                '_token': token
            },
            success: function(response) {
                if (response.status == "success") {
                    loadTable();
                }
            }
        });
    });
</script>
@endpush
