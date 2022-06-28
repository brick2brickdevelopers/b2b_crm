@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12 text-right bg-title-right">
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

    <link rel="stylesheet"
        href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
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
                            <h5>Account Filters</h5>
                            <div id="reportrange" class="form-control reportrange">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down pull-right"></i>
                            </div>
                            <input type="hidden" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                                value="" />
                            <input type="hidden" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                                value="" />
                        </div>
                        <div class="col-xs-12 m-t-20">
                            <div class="form-group">
                                <label class="control-label">Select Agent</label>
                                <select class="selectpicker form-control" name="" id="agent_id" data-style="">
                                    <option value="all">@lang('modules.lead.all')</option>
                                    @foreach ($employees as $item)
                                        <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="">Select Call Status</label>
                                <select class="selectpicker form-control" data-placeholder="" id="call_outcome"
                                    name="call_outcome">
                                    <option value="all">@lang('modules.lead.all')</option>
                                    @foreach (call_outcome() as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Select Call Type</label>
                                <select class="form-control selectpicker" name="call_type" id="call_type"
                                    data-style="form-control">
                                    <option value="all">All</option>
                                    <option value="1">Auto</option>
                                    <option value="0">manual</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Select Call Source</label>
                                <select class="form-control selectpicker" name="" id="call_source"
                                    data-style="form-control">
                                    <option value="all">All</option>
                                    <option value="0">Outgoing</option>
                                    <option value="1">Incoming</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Campaign Status Filter</label>
                                <select class="form-control selectpicker" name="" id="campaign_status"
                                    data-style="form-control">
                                    <option value="all">All</option>
                                    <option value="available">Available</option>
                                    <option value="completed">Completed</option>
                                    <option value="followup">Followup</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Select Campaign</label>
                                <select class="form-control selectpicker" name="" id=""
                                    data-style="form-control">
                                    <option value="all">All</option>
                                    @foreach ($campaigns as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Select Call Purpose</label>
                                <select class="form-control selectpicker" name="" id=""
                                    data-style="form-control">
                                    <option value="all">All</option>
                                    @foreach ($call_purpose as $item)
                                        <option value="{{ $item->id }}">{{ $item->purpose }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="form-group">
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
                {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
            </div>
        </div>
    </div>
</div>
<!-- .row -->
{{-- Ajax Modal --}}
<div class="modal fade bs-modal-md in" id="followUpModal" role="dialog" aria-labelledby="myModalLabel"
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
{{-- Ajax Modal Ends --}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="//cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
{{-- <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script> --}}
{{-- <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script> --}}
{{-- <script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script> --}}
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/daterange-picker/daterangepicker.js') }}"></script>
{!! $dataTable->scripts() !!}

<script>
    $('#selectAll').change(function(e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
</script>

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
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

    });
    var table;

    function tableLoad() {
        window.LaravelDataTables["callreport-table"].draw();
    }

    $(function() {
        tableLoad();
        $('#reset-filters').click(function() {
            $('#filter-form')[0].reset();
            $('#start-date').val('');
            $('#end-date').val('');
            $('#filter-form').find('select').selectpicker('render');
            $.easyBlockUI('#callreport-table');
            $('#reportrange span').html('');
            tableLoad();
            $.easyUnblockUI('#callreport-table');
        })
        var table;
        $('#apply-filters').click(function() {
            $('#callreport-table').on('preXhr.dt', function(e, settings, data) {
                var startDate = $('#start-date').val();
                if (startDate == '') {
                    startDate = null;
                }
                var endDate = $('#end-date').val();
                if (endDate == '') {
                    endDate = null;
                }
                var client = $('#client').val();
                var agent = $('#agent_id').val();
                var call_outcome = $('#call_outcome').val();
                var call_type = $('#call_type').val();
                var call_source = $('#call_source').val();
                var campaign_status = $('#campaign_status').val();
                var source_id = $('#source_id').val();

                data['startDate'] = startDate;
                data['endDate'] = endDate;
                data['call_type'] = call_type;
                data['call_source'] = call_source;
                data['agent'] = agent;

            });
            $.easyBlockUI('#callreport-table');
            tableLoad();
            $.easyUnblockUI('#callreport-table');
        });

        $('body').on('click', '.sa-params', function() {
            var id = $(this).data('user-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.confirmation.deleteLead')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.deleteConfirmation')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('admin.leads.destroy', ':id') }}";
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
                                var leadData = response.data;
                                $('#totalLeads').html(response.data
                                    .totalLeadsCount);
                                $('#totalClientConverted').html(response.data
                                    .totalClientConverted);
                                $('#pendingLeadFollowUps').html(response.data
                                    .pendingLeadFollowUps);
                                $.easyBlockUI('#callreport-table');
                                tableLoad();
                                $.easyUnblockUI('#callreport-table');
                            }
                        }
                    });
                }
            });
        });
    });

    function changeStatus(leadID, statusID) {
        var url = "{{ route('admin.leads.change-status') }}";
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {
                '_token': token,
                'leadID': leadID,
                'statusID': statusID
            },
            success: function(response) {
                if (response.status == "success") {
                    $.easyBlockUI('#callreport-table');
                    tableLoad();
                    $.easyUnblockUI('#callreport-table');
                }
            }
        });
    }

    $('.edit-column').click(function() {
        var id = $(this).data('column-id');
        var url = '{{ route('admin.taskboard.edit', ':id') }}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: "GET",
            success: function(response) {
                $('#edit-column-form').html(response.view);
                $(".colorpicker").asColorPicker();
                $('#edit-column-form').show();
            }
        })
    })

    function followUp(leadID) {

        var url = '{{ route('admin.leads.follow-up', ':id') }}';
        url = url.replace(':id', leadID);

        $('#modelHeading').html('Add Follow Up');
        $.ajaxModal('#followUpModal', url);
    }
    $('.toggle-filter').click(function() {
        $('#ticket-filters').toggle('slide');
    })

    function exportData() {

        var client = $('#client').val();
        var followUp = $('#followUp').val();

        var url = '{{ route('admin.leads.export', [':followUp', ':client']) }}';
        url = url.replace(':client', client);
        url = url.replace(':followUp', followUp);

        window.location.href = url;
    }
</script>
@endpush
