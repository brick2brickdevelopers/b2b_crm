@extends('layouts.app')

@section('page-title')
    <style>
        .card-body {
            padding: 15px
        }
    </style>
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} Dashboard</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->

        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0px !important;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">

    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterange-picker/daterangepicker.css') }}" />

    <link rel="stylesheet" href="https://voc.tdas.in/plugins/bower_components/custom-select/custom-select.css">
@endpush

@section('content')

    <div class="row dashboard-stats">
        <div class="col-md-12 m-b-30">
            {{-- <div class="white-box">
            <div class="col-md-4 text-center">
                <h4><span class="text-dark" id="totalLeads">{{ $totalLeads }}</span> <span
                        class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalLeads')</span></h4>
            </div>
            <div class="col-md-4 text-center b-l">
                <h4><span class="text-info" id="totalClientConverted">{{ $totalClientConverted }}</span> <span
                        class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalConvertedClient')</span>
                </h4>
            </div>
            <div class="col-md-4 text-center b-l">
                <h4><span class="text-warning" id="pendingLeadFollowUps">{{ $pendingLeadFollowUps }}</span> <span
                        class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalPendingFollowUps')</span>
                </h4>
            </div>
        </div> --}}
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="white-box">

                <div class="col-xs-12">
                    <div class="row dashboard-stats">
                        <div class="col-md-12 m-b-30">
                            <div class="white-box">
                                <div class="col-md-2 text-center">
                                    <h4><span class="text-dark" id="totalWorkingDays">4</span> <span
                                            class="font-12 text-muted m-l-5"> Total Calls</span></h4>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h4><span class="text-dark" id="totalWorkingDays">4</span> <span
                                            class="font-12 text-muted m-l-5"> Incomming Calls</span></h4>
                                </div>
                                <div class="col-md-2 b-l text-center">
                                    <h4><span class="text-success" id="daysPresent">4</span> <span
                                            class="font-12 text-muted m-l-5"> Outgoing Calls</span></h4>
                                </div>
                                <div class="col-md-2 b-l text-center">
                                    <h4><span class="text-danger" id="daysLate">4</span> <span
                                            class="font-12 text-muted m-l-5"> Both Answered</span></h4>
                                </div>
                                <div class="col-md-2 b-l text-center">
                                    <h4><span class="text-warning" id="halfDays">4</span> <span
                                            class="font-12 text-muted m-l-5"> Agent UnAnswered:</span></h4>
                                </div>
                                <div class="col-md-2 b-l text-center">
                                    <h4><span class="text-info" id="absentDays">4</span> <span
                                            class="font-12 text-muted m-l-5"> Cust. Ans - Agent UnAns</span></h4>
                                </div>
                                {{-- <div class="col-md-2 b-l text-center">
                                <h4><span class="text-primary" id="holidayDays">4</span> <span class="font-12 text-muted m-l-5"> Cust. UnAns - Agent Ans</span></h4>
                            </div> --}}
                            </div>
                        </div>

                    </div>
                </div>
                {{-- Start Filter --}}

                <div class="row m-3">
                    <div class="col-md-4">
                        <div id="reportrange" class="form-control reportrange">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down pull-right"></i>
                        </div>

                        <input type="hidden" class="form-control" id="start-date" placeholder="Start Date" value="">
                        <input type="hidden" class="form-control" id="end-date" placeholder="End Date" value="">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="check_action" aria-label="Example select with button addon">
                            <option value="">Select Campaign</option>
                            @if (!empty($campaigns))
                                @foreach ($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3" id="assign_to_campaign_section">
                        <select class="form-control" id="assign_to_campaign" data-toggle="select2"
                            data-placeholder="Choose Campaigns">
                            <option value="">Choose Agent</option>
                            @if (!empty($agents))
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->user_id }}">{{ $agent->user->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="apply-filter" class="btn btn-primary btn-sm"><i
                            class="fa fa-sliders"></i> Filter</button>
                    </div>
                </div>

                {{-- End Filter --}}
                {{-- <div class="px-2"> --}}
                <div class="row">
                    <div class="col-xs-12" id="leadSeache-data"></div>
                </div>


                <div class="row">
                    <div class="main-search" id="main-search">
                        <div class="col-md-9" style="background-color: #f6f7f9; margin-top:10px; border-radius:10px">
                            <div class="card">
                                <div class="card-body">
                                    <div>
                                        <div class="simplebar-wrapper" style="margin: 0px;">
                                            <div class="simplebar-height-auto-observer-wrapper">
                                                <div class="simplebar-height-auto-observer"></div>
                                            </div>
                                            <div class="simplebar-mask">
                                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                    <div class="simplebar-content-wrapper"
                                                        style="height: 100%; overflow: hidden;">
                                                        <div class="simplebar-content" style="padding: 0px;">
                                                            <div class="col-md-12">
                                                                <ul class="nav customtab nav-tabs nav-justified mb-3"
                                                                    role="tablist">
                                                                    <li role="presentation" class="active">
                                                                        <a href="#available-tab"
                                                                            onclick="loadTabData('available-data',0)"
                                                                            data-name="available-data" data-type="0"
                                                                            aria-controls="available-tab" role="tab"
                                                                            data-toggle="tab" aria-expanded="true"><i
                                                                                class="ti-ticket"></i> Available</a>
                                                                    </li>
                                                                    <li role="presentation" class=""><a
                                                                            href="#completed-tab"
                                                                            data-name="completed-data"
                                                                            onclick="loadTabData('completed-data',1)"
                                                                            data-type="1" aria-controls="completed-tab"
                                                                            role="tab" data-toggle="tab"
                                                                            aria-expanded="false"><i
                                                                                class="icon-graph"></i> Completed</a>
                                                                    </li>
                                                                    <li role="presentation" class=""><a
                                                                            href="#follow-tab" data-name="follow-data"
                                                                            onclick="loadTabData('follow-data',2)"
                                                                            data-type="2" aria-controls="follow-tab"
                                                                            role="tab" data-toggle="tab"
                                                                            aria-expanded="false"><i
                                                                                class="icon-graph"></i> Followup</a>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="tab-content">
                                                                <div role="tabpanel" class="tab-pane fade active in"
                                                                    id="available-tab">
                                                                    <div class="col-sm-12 available-data">
                                                                        {!! $html->table() !!}
                                                                    </div>
                                                                </div>

                                                                <div role="tabpanel" class="tab-pane" id="completed-tab">
                                                                    <div class="col-sm-12 completed-data">
                                                                        {{-- {!! $html->table() !!} --}}
                                                                    </div>
                                                                </div>
                                                                <div role="tabpanel" class="tab-pane" id="follow-tab">
                                                                    <div class="col-sm-12 follow-data">
                                                                        {{-- {!! $html->table() !!} --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="simplebar-placeholder" style="width: 817px;"></div>
                                        </div>
                                        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                        </div>
                                        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                            <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">

                            @foreach ($callPurposes as $callPurpose)
                                <div class="card"
                                    style="background-color: #f6f7f9; margin-top:10px; border-radius:10px">
                                    <div class="card-body">
                                        <div class="row align-items-center my-2">

                                            <div class="col-lg-6">
                                                <h5 class="text-muted font-weight-normal mt-0 text-truncate"
                                                    title="Campaign Sent">{{ $callPurpose->purpose }}</h5>
                                                <a class="btn btn-light prayer_request_redirect"
                                                    href="http://crm-dev.cleverstack.in/crm/admin/leads/call-log-reports?startdate=05/15/2022&amp;enddate=05/15/2022&amp;purpose=1"><span
                                                        id="prayer_request_count">0</span></a>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="text-right" style="position: relative;">
                                                    <div id="campaign-sent-chart" data-colors="#727cf5"
                                                        style="min-height: 60px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- <div class="table-responsive">
                                    {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                                </div> --}}
                        </div>
                    </div>
                </div>

                <div class="row" style="background-color: #f6f7f9; margin-top:10px; border-radius:10px">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="col-md-1">
                                    <h4 class="mt-0">Session</h4>
                                </div>
                                <div class="col-md-1">Active : <span class="mt-0" id="sessionActiveCount">0</span>
                                </div>
                                <div class="col-md-1">Inactive : <span class="mt-0" id="sessionInactiveCount">9</span>
                                </div>
                                <div class="col-md-3">
                                    <select class="selectpicker form-control" data-placeholder="Choose Agents"
                                        id="agent_id" name="agent_id" style="display: none;">
                                        <option value="all">All</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="selectpicker form-control" data-placeholder="Choose Agents"
                                        id="agent_id" name="agent_id" style="display: none;">
                                        <option value="all">Select Campaign</option>
                                        @if (!empty($campaigns))
                                            @foreach ($campaigns as $campaign)
                                                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3">

                                    <select class="selectpicker form-control" data-placeholder="Choose Agents"
                                        id="agent_id" name="agent_id" style="display: none;">
                                        <option value="all">Select Call Type</option>
                                    </select>
                                </div>

                                <hr>


                                <table class="table table-bordered table-hover">
                                    <thead>

                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Session</th>
                                        <th>Total Calls</th>
                                        <th>Total Received</th>
                                        <th>Total Missed calls</th>
                                        <th>Total Call Duration</th>
                                        <th>Total Manual Dial</th>
                                        <th>Total Auto Dial</th>
                                        <th>Total Prayer Request</th>
                                        <th>Total Donations</th>
                                        <th>Total Will Donate</th>
                                    </thead>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
                {{-- </div> --}}


            </div>
        </div>
    </div>
    <!-- .row -->

    <div id="callLogEditModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-top">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="callLogEditModalLabel">Call Log Edit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="editCallLogForm" action="javascript:void(0)">
                    <input type="hidden" id="callLogEditId" name="callLogEditId" value="" />
                    <input type="hidden" id="callLogRadioValue" name="callLogRadioValue" value="" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="create_at">Call Status</label>
                                    <select class="form-control select2" name="call_outcome" id="call_outcome"
                                        data-toggle="select2" data-placeholder="Please select..">
                                        <option value="">Please select..</option>
                                        <option value="Answered">Answered</option>
                                        <option value="Not Answered">Not Answered</option>
                                        <option value="Switched Off">Switched Off</option>
                                        <option value="Not working">Not working</option>
                                        <option value="Wrong number">Wrong number</option>
                                        <option value="Call back">Call back</option>
                                        <option value="Not Reachable">Not Reachable</option>
                                        <option value="add">Add Value</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="create_at">Call Purpose</label>
                                    <select class="select2 select2-multiple" name="call_purpose" id="call_purpose"
                                        multiple="multiple" data-toggle="select2" data-placeholder="Please select..">
                                        <option value="">Please select..</option>
                                        <option value="Add Donation">Add Donation</option>
                                        <option value="Add Prayer Request">Add Prayer Request</option>
                                        <option value="Will Donate">Will Donate</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="call_description" id="call_description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            <button type="submit" id="editclForm" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterange-picker/daterangepicker.js') }}"></script>
    <script src="https://voc.tdas.in/plugins/bower_components/custom-select/custom-select.min.js"></script>




    <script>
        $('#nav-tab li:first-child button').tab('show')
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


        function oppenRadioModal(id, mobile) {

            console.log("called")

            $('#callLogEditModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#callLogRadioValue').val(id);
            $('#call_outcome').val(null).trigger('change');
            $('#call_purpose').val(null).trigger('change');
            $('#call_description').val('');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })
            $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: "https://crm-dev.cleverstack.in/crm/admin/lead/call_log_edit_radio",
                    data: {
                        mobile: mobile
                    },
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true
                })
                // using the done promise callback
                .done(function(data) {
                    $('#callLogEditId').val('');
                    if (data.status == true) {
                        $('.optn').removeAttr('selected');
                        $('#call_outcome').val(data.logData.call_outcome);
                        $('#call_outcome').trigger('change');
                        $('#call_purpose').val(data.logData.call_purpose);
                        //$('#edit_issue').val(data.message.issue); // Select the option with a value of '1'
                        $('#call_purpose').trigger('change'); // Notify any JS components that the value changed
                        $('#call_description').val(data.logData.description);

                        $('#callLogEditId').val(data.logData.id);
                    }
                    $('#callLogEditModal').modal('show');
                })

                // using the fail promise callback
                .fail(function(data) {
                    console.log(data);
                });
        }

        $(".select2").select2({
            formatNoMatches: function() {
                return "No record found.";
            }
        });
    </script>





    {!! $html->scripts() !!}
    <script>
        const tableHtml = {
            "serverSide": true,
            "processing": true,
            "ajax": "",
            "columns": [{
                "name": "id",
                "data": "id",
                "title": "Id",
                "orderable": true,
                "searchable": true
            }, {
                "name": "name",
                "data": "lead.client_name",
                "title": "Name",
                "orderable": true,
                "searchable": true
            }, {
                "name": "Phone",
                "data": "lead.mobile",
                "title": "Phone",
                "orderable": true,
                "searchable": true
            }, {
                "name": "action",
                "data": "action",
                "title": "action",
                "orderable": true,
                "searchable": true
            }]
        }

        $('#tab-table').on('preXhr.dt', function(e, settings, data) {

            data['type'] = 0;
            var start_date = $('#start-date').val();
            var end_date = $('#end-date').val();
            var assign_to_campaign = $('#assign_to_campaign').val();
            var check_action = $('#check_action').val();
            data['start-date'] = start_date;
            data['end-date'] = end_date;
            data['assign_to_campaign'] = assign_to_campaign;
            data['check_action'] = check_action;

        });

        function tableLoad() {
            window.LaravelDataTables["tab-table"].draw();

        }


        function loadTabData(name, type) {
            // 'available-data'
            // 'completed-data'
            // 'follow-data'
            if (name === 'completed-data') {
                $('.available-data').html(null)
                $("." + name).html('{!! $html->table() !!}')
                $('#tab-table').on('preXhr.dt', function(e, settings, data) {
                    data['type'] = 1;
                    var start_date = $('#start-date').val();
                    var end_date = $('#end-date').val();
                    var assign_to_campaign = $('#assign_to_campaign').val();
                    var check_action = $('#check_action').val();
                    data['start-date'] = start_date;
                    data['end-date'] = end_date;
                    data['assign_to_campaign'] = assign_to_campaign;
                    data['check_action'] = check_action;
                });
                $('#tab-table').DataTable(tableHtml);
            }
            if (name === 'follow-data') {
                $('.available-data').html(null)
                $('.completed-data').html(null)
                $("." + name).html('{!! $html->table() !!}')
                $('#tab-table').on('preXhr.dt', function(e, settings, data) {
                    data['type'] = 2;
                    var start_date = $('#start-date').val();
                    var end_date = $('#end-date').val();
                    var assign_to_campaign = $('#assign_to_campaign').val();
                    var check_action = $('#check_action').val();
                    data['start-date'] = start_date;
                    data['end-date'] = end_date;
                    data['assign_to_campaign'] = assign_to_campaign;
                    data['check_action'] = check_action;
                });
                $('#tab-table').DataTable(tableHtml);
            }
            if (name === 'available-data') {
                $('.follow-data').html(null)
                $('.completed-data').html(null)
                $("." + name).html('{!! $html->table() !!}')
                $('#tab-table').on('preXhr.dt', function(e, settings, data) {
                    data['type'] = 0;
                    var start_date = $('#start-date').val();
                    var end_date = $('#end-date').val();
                    var assign_to_campaign = $('#assign_to_campaign').val();
                    var check_action = $('#check_action').val();
                    data['start-date'] = start_date;
                    data['end-date'] = end_date;
                    data['assign_to_campaign'] = assign_to_campaign;
                    data['check_action'] = check_action;
                });
                $('#tab-table').DataTable(tableHtml);
            }

        }

        // $('a[data-toggle="tab"').on('shown.bs.tab', function(event) {
        //     console.log(event.relatedTarget)

        // })
        let name = 'available-data'
        let type = "0"
        $('a[data-toggle="tab"').on('click', function(event) {

            name = $(this).data('name')
            type = $(this).data('type')



        })

        $('#apply-filter').click(function() {
            loadTabData(name, type)
            
        });
    </script>

    {{-- <script>
          $('#apply-filter').click(function () {
             $('#main-search').hide();
       showTable();
    });

   

    function showTable() {

        var check_action = $('#check_action').val();
        var assign_to_campaign = $('#assign_to_campaign').val();

       
        var url = '{{route('admin.leads.dashboard.search')}}';

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                check_action: check_action,
                assign_to_campaign: assign_to_campaign
                
            },
            url: url,
            success: function (response) {
               $('#leadSeache-data').html(response.data);
            }
        });
       
    }

    showTable();

    </script> --}}
@endpush
