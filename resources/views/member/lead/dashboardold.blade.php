@extends('layouts.member-app')

@section('page-title')

<style>
    .card-body {
        padding: 15px
    }
</style>
<div class="row bg-title ">
    <!-- .page title -->
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 bg-title-left " style="width: 100% !important;">
        <h4 class="page-title"><i class="{{ $pageIcon }}"></i>Employee {{ __($pageTitle) }} Dashboard</h4>
    </div>
    <!-- /.page title -->
    <!-- .breadcrumb -->
    <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12 text-right bg-title-right">
        {{-- <a href="{{ route('admin.leads.create') }}"
            class="btn btn-outline btn-success btn-sm">@lang('modules.lead.addNewLead') <i class="fa fa-plus"
                aria-hidden="true"></i></a>

        <a href="{{ route('admin.leads.kanbanboard') }}"
            class="btn btn-outline btn-primary btn-sm">@lang('modules.lead.kanbanboard')
        </a>

        <a href="{{ route('admin.lead-form.index') }}"
            class="btn btn-outline btn-inverse btn-sm">@lang('modules.lead.leadForm') <i class="fa fa-pencil"
                aria-hidden="true"></i></a> --}}

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

<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterange-picker/daterangepicker.css') }}" />

<link rel="stylesheet" href="https://voc.tdas.in/plugins/bower_components/custom-select/custom-select.css">

@endpush

@section('content')


<div class="row">
    <div class="col-xs-12">
        <div class="white-box">

            <div class="col-xs-12">
                <div class="row dashboard-stats">
                    <div class="col-md-12 m-b-30">
                        <div class="white-box">
                            <div class="col-md-2 text-center">
                                <h4><span class="text-dark" id="totalWorkingDays">4</span> <span class="font-12 text-muted m-l-5"> Total Calls</span></h4>
                            </div>
                            <div class="col-md-2 text-center">
                                <h4><span class="text-dark" id="totalWorkingDays">4</span> <span class="font-12 text-muted m-l-5"> Incomming Calls</span></h4>
                            </div>
                            <div class="col-md-2 b-l text-center">
                                <h4><span class="text-success" id="daysPresent">4</span> <span class="font-12 text-muted m-l-5"> Outgoing Calls</span></h4>
                            </div>
                            <div class="col-md-2 b-l text-center">
                                <h4><span class="text-danger" id="daysLate">4</span> <span class="font-12 text-muted m-l-5"> Both Answered</span></h4>
                            </div>
                            <div class="col-md-2 b-l text-center">
                                <h4><span class="text-warning" id="halfDays">4</span> <span class="font-12 text-muted m-l-5"> Agent UnAnswered:</span></h4>
                            </div>
                            <div class="col-md-2 b-l text-center">
                                <h4><span class="text-info" id="absentDays">4</span> <span class="font-12 text-muted m-l-5"> Cust. Ans - Agent UnAns</span></h4>
                            </div>
                            {{-- <div class="col-md-2 b-l text-center">
                                <h4><span class="text-primary" id="holidayDays">4</span> <span class="font-12 text-muted m-l-5"> Cust. UnAns - Agent Ans</span></h4>
                            </div> --}}
                        </div>
                    </div>
    
                </div>
            </div>
     
            {{-- <div class="px-2"> --}}
                <div class="row">
                    <div class="col-xs-12"  id="search-data"></div>
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
                                                            <ul class="nav nav-tabs nav-justified mb-3">
                                                                <li class="nav-item active">
                                                                    <a href="#available" id="available-tab"
                                                                        data-toggle="tab" aria-expanded="false"
                                                                        class="nav-link ">
                                                                        <i
                                                                            class="mdi mdi-home-variant d-md-none d-block"></i>
                                                                        <span class="d-none d-md-block">Available (<span
                                                                            id="total_leads_count">{{ $totalAvailable >0 ? $totalAvailable:0 }}</span>)</span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="#completed_leads" id="leads-tab"
                                                                        data-toggle="tab" aria-expanded="true"
                                                                        class="nav-link">
                                                                        <i
                                                                            class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                        <span class="d-none d-md-block">Completed
                                                                            (<span
                                                                            id="total_leads_count">{{ $totalCompleted >0 ? $totalCompleted:0 }}</span>)
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="#follow_up_leads" id="close-task"
                                                                        data-toggle="tab" aria-expanded="true"
                                                                        class="nav-link">
                                                                        <i
                                                                            class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                        <span class="d-none d-md-block">Follow Up

                                                                            (<span
                                                                            id="total_leads_count">{{ $totalFollow >0 ? $totalFollow:0 }}</span>)
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                            </ul>
    
                                                            <div class="tab-content">
                                                                <div class="tab-pane tab_status active" id="available">
                                                                    <div class="table-responsive">
                                                                        <div id="avilable-server-datatable_wrapper"
                                                                            class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <table class="table tabel-bordered">
                                                                                        <thead class="thead-light">
                                                                                            <tr role="row">
                                                                                                <th>
                                                                                                    ID</th>
                                                                                                <th>
                                                                                                    Name</th>
                                                                                                <th>
                                                                                                    Phone</th>
                                                                                               
                                                                                                <th>
                                                                                                    Action</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            @if (!empty($leads))
                                                                                            @foreach ($leads as $lead)
                                                                                            <tr>
                                                                                                <td>{{ $lead->id}}</td>
                                                                                                <td>{{
                                                                                                    $lead->lead->client_name
                                                                                                    }}
                                                                                                </td>
                                                                                                <td>{{
                                                                                                    $lead->lead->mobile}}
                                                                                                </td>
                                                                                               
                                                                                                <td>
                                                                                                    
                                                                                                    @if(empty(user()->sip_pass))
                                                                                                        
                                                                                                            <a class="mr-1"
                                                                                                            style="margin-right: 5px"
                                                                                                            href="javascript:void(0);"
                                                                                                            onclick='oppenRadioModal("{{ $lead->id }}"
                                                                                                            , "{{ $lead->lead->mobile }}"
                                                                                                            )'><i class=" fa
                                                                                                            fa-plus"></i></a>
                                                                                                            
                                                                                                        <a href="tel:{{ $lead->lead->mobile }}"
                                                                                                            ><i
                                                                                                                class="fa fa-phone"></i></a>
                                                                                                        
                                                                                                    @else
                                                                                                        <a class="mr-1"
                                                                                                            style="margin-right: 5px"
                                                                                                            href="javascript:void(0);"
                                                                                                            onclick='updateCallDetail("{{ $lead->id }}"
                                                                                                            , "{{ $lead->lead->mobile }}"
                                                                                                            )'><i class=" fa
                                                                                                            fa-plus"></i></a>
                                                                                                            
                                                                                                        <a data-toggle="modal" data-target="#exampleModalLong{{$lead->id}}"
                                                                                                            ><i
                                                                                                                class="fa fa-phone"></i></a>
                                                                                                    @endif
                                                                                                     
    
                                                                                                </td>
                                                                                            </tr>


                                                                                            
                                                                                            <!-- Modal -->
                                                                                            <div class="modal fade" id="exampleModalLong{{$lead->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                                                                <div class="modal-dialog" role="document">
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header">
                                                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">
                                                                                                    {{ $lead->lead->mobile }}
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            @endforeach
                                                                                            @endif
    
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
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
                            <div class="card" style="background-color: #f6f7f9; margin-top:10px; border-radius:10px">
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
                        </div>
                    </div>
                </div>

                
                {{--
            </div> --}}


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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
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


    function oppenRadioModal(id,mobile)
    {

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
        data:{mobile:mobile} ,
        dataType: 'json', // what type of data do we expect back from the server
        encode: true
    })
    // using the done promise callback
    .done(function(data) {
    $('#callLogEditId').val('');
    if(data.status==true){
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
    formatNoMatches: function () {
    return "No record found.";
    }
    });

</script>

@endpush