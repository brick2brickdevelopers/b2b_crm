@push('head-script')
<style>
.dataTables_wrapper 
.dataTables_paginate 
.paginate_button {
    padding:0px !important;
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

<div class="main-search">
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
                                                <a href="" id="available-tab"
                                                    data-toggle="tab" aria-expanded="false"
                                                    class="nav-link ">
                                                    <i
                                                        class="mdi mdi-home-variant d-md-none d-block"></i>
                                                    <span class="d-none d-md-block">Available (<span
                                                        id="total_leads_count">{{ $totalAvailable >0 ? $totalAvailable:0 }}</span>) </span>
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
                                                                <table class="table tabel-bordered" id="table_id">
                                                                    <thead class="thead-light">
                                                                        <tr role="row">
                                                                            <th>
                                                                                ID</th>
                                                                            <th>
                                                                                Name</th>
                                                                            <th>
                                                                                Phone</th>
                                                                            {{-- <th>
                                                                                Campaign Status</th>
                                                                            <th>
                                                                                Lead Status</th> --}}
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
                                                                            {{-- <td>
                                                                                {{<select
                                                                                    class="form-control">
                                                                                    <option>Available
                                                                                    </option>
                                                                                    <option>Completed
                                                                                    </option>
                                                                                    <option>Follow
                                                                                    </option>
                                                                                </select>}}
                                                                            </td>
                                                                            <td>


                                                                                <select
                                                                                    class="form-control">
                                                                                    <option>Assigned
                                                                                    </option>
                                                                                    <option>Opened
                                                                                    </option>
                                                                                    <option>Converted
                                                                                    </option>
                                                                                    <option>Follow
                                                                                    </option>
                                                                                    <option>Closed
                                                                                    </option>
                                                                                </select>

                                                                            </td> --}}
                                                                            <td><a class="mr-1"
                                                                                    style="margin-right: 5px"
                                                                                    href="javascript:void(0);"
                                                                                    onclick='oppenRadioModal("{{ $lead->id }}"
                                                                                    , "{{ $lead->lead->mobile }}"
                                                                                    )'><i class=" fa
                                                                                    fa-plus"></i></a>
                                                                                <a href="javascript:void(0);"
                                                                                    onclick="manualsinglecall(1234567890)"><i
                                                                                        class="fa fa-phone"></i></a>

                                                                            </td>
                                                                        </tr>
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
            {{-- <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
            </div> --}}
    </div>
</div>


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
<script type="text/javascript">
    
     $('#reset-filters').click(function () {
       showTable();
       $('#main-search').hide();
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    function showTable() {

        var start_date = $('#start-date').val();
        var end_date = $('#end-date').val();
        var assign_to_campaign = $('#assign_to_campaign').val();
        var check_action = $('#check_action').val();
      
        //refresh counts
        var url = '{!!  route('admin.leads.dashboard.search') !!}';

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            data: {
                '_token': token,
                start_date : start_date,
                end_date : end_date,
                assign_to_campaign : assign_to_campaign,
                check_action : check_action
            },
            url: url,
            success: function (response) {
               $('#search-data').html(response.data);
              
            }
        });

    }

    showTable();
   

</script>

  <script>

$(document).ready( function () {
    $('#table_id').DataTable();
} );
  </script>

<script type="text/javascript">
    
    $('#available-tab').click(function () {
      showTable();
      $('#main-search').hide();
   });

   $(".select2").select2({
       formatNoMatches: function () {
           return "{{ __('messages.noRecordFound') }}";
       }
   });

   function showTable() {

       var available = 0;
     
     
       //refresh counts
       var url = '{!!  route('admin.leads.dashboard.available') !!}';

       var token = "{{ csrf_token() }}";

       $.easyAjax({
           type: 'POST',
           data: {
               '_token': token,
               available : available,
               
           },
           url: url,
           success: function (response) {
              $('#available-data').html(response.data);
             
           }
       });

   }

   showTable();
  

</script>
@endpush
