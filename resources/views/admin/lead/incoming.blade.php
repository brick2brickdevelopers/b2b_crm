@php 
use App\EventCategory;
use App\EventType;           
use App\User;           
use App\Event;           
use App\Lead;           

$categories = EventCategory::all();
$eventTypes = EventType::all();

$employees = User::allEmployees();
        $events = Event::all();
        $clients = User::allClients();
        $leads = Lead::all();
        $unique_id = uniqid();

@endphp
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">


@if ($type == 'call')
    <div class="row">
        @php
            $call_sources = $calls ? $calls->call_source : '';
            $call_source = $log->call_sources == 1 ? 'Incoming' : 'Outgoing';
        @endphp
        <div class="col-md-10"><strong style="font-size: 18px;">Mobile: {{ $mobile }} <span
                    style="margin-left: 20px;"> Call Type: {{ $call_source }} </span></strong></div>
    </div>
    <div class="row">
        <div class="col-md-2 modal-box1">
            <form id="incoming_call_lead_details_save">
                @csrf
                <input type="hidden" name="log_id" value="{{ $log->id }}">

                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="description">Mobile *</label>
                        <input class="form-control" readonly value="{{ $mobile }}" name="mobile"
                            id="incoming_mobile"></input>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="description">Name *</label>
                        <input class="form-control" {{ $lead ? 'readonly' : '' }}
                            value="{{ $lead ? $lead->client_name : '' }}" name="name" id="incoming_name"></input>

                    </div>
                </div>

                {{-- <div class="col-lg-12">
                    <div class="form-group">
                        <label for="description">Email *</label>
                        <input class="form-control" {{ $lead ? 'readonly' : '' }}
                            value="{{ $lead ? $lead->email : '' }}" name="email" id="incoming_name"></input>
                    </div>
                </div> --}}
                @if (!$lead)
                    <div class="col-lg-12">
                        <center>
                            <button type="submit" id="" class="btn btn-success btn-sm">SAVE</button>

                        </center>
                    </div>
                @endif
            </form>


        </div>



        <div class="col-md-6 modal-box">
            <form id="incoming_call_details_save">
                @csrf

                {{-- <input type="hidden" id="incoming_member_id" name="member_id" value="{{ $lead ? $lead->id : '' }}" /> --}}
                <input type="hidden" id="incoming_manual_callLog_id" name="log_id" value="{{ $log->id }}" />

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="create_at">Call Type</label>
                            <select {{ $lead ? '' : 'disabled' }} class="form-control select2" name="call_source"
                                id="call_source" data-toggle="select2" data-placeholder="Please select.." required>
                                <option value="">Please select..</option>
                                <option value="1" {{ $log->call_sources == 1 ? 'selected' : '' }}>Incoming</option>
                                <option value="0" {{ $log->call_sources == 0 ? 'selected' : '' }}>Outgoing</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="create_at">Call Purpose</label>
                            <select {{ $lead ? '' : 'disabled' }} class="form-control select2" name="call_purpose"
                                id="incoming_call_purpose" onchange="incoming_call_purpose_change(this.value)"
                                data-toggle="select2" data-placeholder="Please select.." required>
                                <option value="">Please select..</option>
                                @foreach ($callperposes as $item)
                                    <option value="{{ $item->id }}">{{ $item->purpose }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="create_at">Call Outcome</label>
                            <select {{ $lead ? '' : 'disabled' }} class="form-control select2" name="outcome"
                                id="incoming_call_purpose" data-toggle="select2" data-placeholder="Please select.."
                                required>
                                <option value="">Please select..</option>
                                @foreach (call_outcome() as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    @isset($isManual)
                        @if ($isManual)
                            <div class="col-lg-12">
                                <div class="form-group">

                                    <label for="create_at">Call Status</label>
                                    <select {{ $lead ? '' : 'disabled' }} class="form-control select2" name="call_status"
                                        data-toggle="select2" data-placeholder="Please select.." required>
                                        <option value="">Please select..</option>
                                        <option value="0">Available</option>
                                        <option value="1">Completed</option>
                                        <option value="2">Follow</option>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="description">Call Duration *</label>
                                    <input class="form-control" name="duration" type="time" />
                                </div>
                            </div>
                        @endif
                    @endisset

                    

                   

                    <div class="form-group">
                        <label for="menu" class="required">Add Event</label>
                       
                    </div>

                    <div class="switchery-demo">
                        <input id="menu-switch" name="menu" type="checkbox" class="js-switch" data-size="small" 
                        data-color="#00c292" value="1" data-switchery="true" style="display: none;">
                       
                        <small style="left: 0px; transition: background-color 0.4s ease 0s, left 0.2s ease 0s;"></small>
                    </span>
                    </div>

                    <div class="col-lg-12" id="incoming_add_donation_div">

                    </div>
                    <div class="col-lg-12" id="incoming_add_prayer_request_div">

                    </div>

                    <div class="col-lg-12">
                        <center>
                            <button type="submit" id="" class="btn btn-success btn-sm">SAVE</button>
                            <a href="#" onclick="removeCallLog({{ $log->id }})"
                                class="btn btn-danger btn-sm">Close</a>
                        </center>
                    </div>

                </div>
            </form>
        </div>


        <div class="col-md-3 modal-box1">
            <?php $found_data = 0; ?>
            <label for="description">Recent calls</label>
            <div data-simplebar style="height: 400px;">
                <div class="col-lg-12" id="recent_calls_div">
                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Purpose</th>
                            </thead>
                            <tbody>
                                @forelse($recent_calls as $key => $value)
                                    <tr
                                        style="{{ $log->id == $value->id ? 'background-color:aquamarine !important' : '' }}">
                                        <td>{{ $value->call_type == 0 ? 'Manual' : 'Auto' }}</td>
                                        <td>{{ $value->duration }}</td>
                                        <td>{{ $value->purpose->purpose ?? 'No Purpose' }}</td>
                                    </tr>
                                @empty
                                    <?php $found_data = 1; ?>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                    <div>
                        <?php if($found_data==1){?>
                        <center>
                            <p>No Data Found</p>

                        </center>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="row" id="add_event">
        <div class="col-lg-8">
            <div class="">
                {!! Form::open(['id' => 'createEvent', 'class' => 'ajax-form', 'method' => 'POST']) !!}
                {{-- <input type= "hidden" name="event_unique_id" value="{{$unique_id}}"> --}}
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label class="required">@lang('modules.events.eventName')</label>
                            <input type="text" name="event_name" id="event_name" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2 ">
                        <div class="form-group">
                            <label>@lang('modules.sticky.colors')</label>
                            <select id="colorselector" name="label_color">
                                <option value="bg-info" data-color="#5475ed" selected>Blue</option>
                                <option value="bg-warning" data-color="#f1c411">Yellow</option>
                                <option value="bg-purple" data-color="#ab8ce4">Purple</option>
                                <option value="bg-danger" data-color="#ed4040">Red</option>
                                <option value="bg-success" data-color="#00c292">Green</option>
                                <option value="bg-inverse" data-color="#4c5667">Grey</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label class="required">@lang('modules.events.where')</label>
                            <input type="text" name="where" id="where" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.tasks.category')
                                <a href="javascript:;" id="add_category"
                                    class="btn btn-xs btn-success btn-outline"><i class="fa fa-plus"></i></a>
                            </label>
                            <select class="select2 form-control" data-placeholder="@lang('modules.clients.category')"
                                id="category_id" name="category_id">
                                <option value="">@lang('messages.pleaseSelectCategory')</option>

                                @forelse($categories as $category)
                                    <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}
                                    </option>
                                @empty
                                    <option value="">@lang('messages.noCategoryAdded')</option>
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label  class="control-label">@lang('modules.events.eventType')
                                <a href="javascript:;"
                                id="createEventType"
                                class="btn btn-xs btn-outline btn-success">
                                    <i class="fa fa-plus"></i> 
                                </a>
                            </label>
                            <select class="select2 form-control" data-placeholder="@lang('modules.clients.clientSubCategory')"  id="event_type_id" name="event_type_id">
                            <option value="">@lang('messages.selectEventType')</option>
                            @forelse($eventTypes as $eventType)
                                <option value="{{ $eventType->id }}">{{ ucwords($eventType->name) }}</option>
                                @empty
                                <option value="">@lang('messages.noCategoryAdded')</option>
                            @endforelse              
                        </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 ">
                        <div class="form-group">
                            <label class="required">@lang('app.description')</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-md-3 ">
                        <div class="form-group">
                            <label class="required">@lang('modules.events.startOn')</label>
                            <input type="text" name="start_date" id="start_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-5 col-md-3">
                        <div class="form-group input-group bootstrap-timepicker timepicker">
                            <label>&nbsp;</label>
                            <input type="text" name="start_time" id="start_time"
                                    class="form-control">
                        </div>
                    </div>
        
                    <div class="col-xs-6 col-md-3">
                        <div class="form-group">
                            <label class="required">@lang('modules.events.endOn')</label>
                            <input type="text" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-5 col-md-3">
                        <div class="form-group input-group bootstrap-timepicker timepicker">
                            <label>&nbsp;</label>
                            <input type="text" name="end_time" id="end_time"
                                    class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12"  id="attendees">
                        <div class="form-group">
                            <label class="col-xs-3 m-t-10 required">@lang('modules.events.addAttendees')</label>
                            <div class="col-xs-7">
                                <div class="checkbox checkbox-info">
                                    <input id="all-employees" name="all_employees" value="true"
                                            type="checkbox">
                                    <label for="all-employees">@lang('modules.events.allEmployees')</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                                    data-placeholder="@lang('modules.messages.chooseMember'), @lang('modules.projects.selectClient')" name="user_id[]">
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ ucwords($emp->name) }} @if($emp->id == $user->id)
                                            (YOU) @endif</option>
                                @endforeach
                            </select>
        
                        </div>
                    </div>
        
                </div>

                <div class="row">
                    <div class="col-xs-12"  id="attendees">
                        <div class="form-group">
                            <label class="col-xs-3 m-t-10 required">@lang('modules.events.addClients')</label>
                            <div class="col-xs-7">
                                <div class="checkbox checkbox-info">
                                    <input id="all-employees" name="all_clients" value="true"
                                            type="checkbox">
                                    <label for="all-employees">@lang('modules.events.allClients')</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                                    data-placeholder="@lang('modules.messages.chooseMember'), @lang('modules.projects.selectClient')" name="user_id[]">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ ucwords($client->name) }} </option>
                                @endforeach
                            </select>
        
                        </div>
        
                        <div class="form-group">
                            <label class="col-xs-3 m-t-10 required">Add Leads</label>
                            <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                                    data-placeholder="select Leads" name="lead_id[]">
                                @foreach($leads as $lead)
                                    <option value="{{ $lead->id }}">{{ $lead->client_name}} </option>
                                @endforeach
                            </select>
        
                        </div>
                    </div>
        
                </div>  
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <div class="checkbox checkbox-info">
                                <input id="repeat-event" name="repeat" value="yes"
                                        type="checkbox">
                                <label for="repeat-event">@lang('modules.events.repeat')</label>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="row" id="repeat-fields" style="display: none">
                    <div class="col-xs-6 col-md-3 ">
                        <div class="form-group">
                            <label>@lang('modules.events.repeatEvery')</label>
                            <input type="number" min="1" value="1" name="repeat_count" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <select name="repeat_type" id="" class="form-control">
                                <option value="day">@lang('app.day')</option>
                                <option value="week">@lang('app.week')</option>
                                <option value="month">@lang('app.month')</option>
                                <option value="year">@lang('app.year')</option>
                            </select>
                        </div>
                    </div>
        
                    <div class="col-xs-6 col-md-3">
                        <div class="form-group">
                            <label>@lang('modules.events.cycles') <a class="mytooltip" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.events.cyclesToolTip')</span></span></span></a></label>
                            <input type="text" name="repeat_cycles" id="repeat_cycles" class="form-control">
                        </div>
                    </div>
                </div>
        
                {!! Form::close() !!}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
                <button type="button" class="btn btn-success save-event waves-effect waves-light">@lang('app.submit')</button>
            </div>
        </div>
    </div>





    <style>
        .modal-box {
            width: 500px;
            border: 2px solid #e1e1e1;
            padding: 10px;
            margin: 10px;
        }

        .modal-box1 {
            width: 300px;
            border: 2px solid #e1e1e1;
            padding: 10px;
            margin: 10px;
        }
    </style>

    <script>
        $("#incoming_call_lead_details_save").submit(function(event) {
            event.preventDefault();
            // $(".btn").prop("disabled", true);
            var incoming_mobile = $('#incoming_mobile').val();
            var incoming_name = $('#incoming_name').val();

            $.easyAjax({
                url: "{{ route('member.leads.storeLead') }}",
                type: "POST",
                data: $('#incoming_call_lead_details_save').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        console.log(response)
                        setTimeout(function() {}, 3500);
                    }
                }
            })
        })
        $("#incoming_call_details_save").submit(function(event) {
            event.preventDefault();
            $.easyAjax({
                url: "{{ route('admin.leads.storeLoggedCallDetails') }}",
                type: "POST",
                data: $('#incoming_call_details_save').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        $("#callDetails").modal('hide');
                        (response)
                        setTimeout(function() {}, 3500);
                    }
                }
            })

        })

        function incoming_call_purpose_change(purpose) {
            $("#incoming_add_donation_div").html("");
            if (purpose) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('member.leads.getForm') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        form: purpose,
                        log_id: "{{ $log->id }}"
                    },
                    success: function(data) {
                        console.log(data)
                        $("#incoming_add_donation_div").html(data);
                        $("#incoming_add_prayer_request_div").html('');
                    },
                    error: function(data) {},
                });
            }








            // alert(purpose)
            // //alert(purpose);
            // if (purpose == "Add Donation" || purpose == "Will Donate") {
            //     $.ajax({
            //         type: "GET",
            //         url: "",
            //         data: {
            //             _token: "{{ csrf_token() }}"
            //         },
            //         success: function(data) {
            //             $("#incoming_add_donation_div").html(data);
            //             $("#incoming_add_prayer_request_div").html('');
            //         },
            //         error: function(data) {},
            //     });

            // } else if (purpose == "Add Prayer Request") {
            //     $.ajax({
            //         type: "GET",
            //         url: "",
            //         data: {
            //             _token: "{{ csrf_token() }}"
            //         },
            //         success: function(data) {
            //             $("#incoming_add_prayer_request_div").html(data);
            //             $("#incoming_add_donation_div").html('');
            //         },
            //         error: function(data) {},
            //     });
            // } else if (purpose == "Will Donate") {
            //     $('#incoming_add_donation_div').show();
            //     //$('#incoming_add_donation_div').hide();
            // } else {
            //     $('#incoming_add_donation_div').hide();
            //     //$('#will_donate_type_div').show();
            // }

        }

        function removeCallLog(id) {
            $.ajax({
                type: "POST",
                url: "{{ route('member.leads.removeLog') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    log_id: "{{ $log->id }}"
                },
                success: function(data) {
                    $("#callDetails").modal('hide');
                },
                error: function(data) {},
            });
        }
    </script>


    {{-- <script>
    function incoming_call_purpose_change(purpose) {
        //alert(purpose);
        if (purpose == "Add Donation" || purpose == "Will Donate") {
            $.ajax({
                type: "GET",
                url: "{{ route('Crm::incoming_donation_form') }}",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $("#incoming_add_donation_div").html(data);
                    $("#incoming_add_prayer_request_div").html('');
                },
                error: function(data) {},
            });

        } else if (purpose == "Add Prayer Request") {
            $.ajax({
                type: "GET",
                url: "{{ route('Crm::incoming_prayer_form') }}",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $("#incoming_add_prayer_request_div").html(data);
                    $("#incoming_add_donation_div").html('');
                },
                error: function(data) {},
            });
        } else if (purpose == "Will Donate") {
            $('#incoming_add_donation_div').show();
            //$('#incoming_add_donation_div').hide();
        } else {
            $('#incoming_add_donation_div').hide();
            //$('#will_donate_type_div').show();
        }

    }

    function incoming_decisionChange(object) {
        var value = object.value;
        if (value == 0) {
            $('#incoming_donation_date_div').hide();
            $('#incoming_will_donate_type_div').hide();
        } else if (value == 2) {
            $('#incoming_donation_date_div').hide();
            $('#incoming_will_donate_type_div').hide();
        } else {
            $('#incoming_donation_date_div').show();
            $('#incoming_will_donate_type_div').show();
        }
    }


    

    $("#incoming_call_lead_details_save").submit(function(event) {
        event.preventDefault();
        $(".btn").prop("disabled", true);
        var incoming_mobile = $('#incoming_mobile').val();
        var incoming_name = $('#incoming_name').val();

        $.ajax({
            type: "POST",
            url: "{{ route('Crm::incoming.lead.create') }}",
            data: {
                '_token': "{{ csrf_token() }}",
                'mobile': incoming_mobile,
                'name': incoming_name,

            },

            success: function(data) {
                $(".btn").prop("disabled", false);
                $('#incoming_member_id').val(data.lead_id);
                $("#incoming_call_purpose").prop("disabled", false);

                //$('#callDetails').modal('toggle');
                $.NotificationApp.send("Success", "Lead Created Successfully.", "top-center",
                    "green",
                    "success");
                setTimeout(function() {}, 3500);

            },
            error: function(data) {
                $.NotificationApp.send("Error", "Lead Not Created.", "top-center", "red", "error");
                setTimeout(function() {}, 3500);
                $(".btn").prop("disabled", false);
            },

        });


    })
</script> --}}
@endif
@if ($type == 'form')
    <div class="row">
        @if (isset($fields))
            @foreach ($fields as $field)
                <div class="col-md-6">
                    <label>{{ ucfirst($field->label) }}</label>
                    <div class="form-group">
                        @if ($field->type == 'text')
                            <input type="text" name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                class="form-control" placeholder="{{ $field->label }}"
                                value="{{ $editUser->custom_fields_data['field_' . $field->id] ?? '' }}">
                        @elseif($field->type == 'password')
                            <input type="password" name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                class="form-control" placeholder="{{ $field->label }}"
                                value="{{ $editUser->custom_fields_data['field_' . $field->id] ?? '' }}">
                        @elseif($field->type == 'number')
                            <input type="number" name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                class="form-control" placeholder="{{ $field->label }}"
                                value="{{ $editUser->custom_fields_data['field_' . $field->id] ?? '' }}">
                        @elseif($field->type == 'textarea')
                            <textarea name="custom_fields_data[{{ $field->name . '_' . $field->id }}]" class="form-control"
                                id="{{ $field->name }}" cols="3">{{ $editUser->custom_fields_data['field_' . $field->id] ?? '' }}</textarea>
                        @elseif($field->type == 'radio')
                            <div class="radio-list">
                                @foreach ($field->values as $key => $value)
                                    <label class="radio-inline @if ($key == 0) p-0 @endif">
                                        <div class="radio radio-info">
                                            <input type="radio"
                                                name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                id="optionsRadios{{ $key . $field->id }}"
                                                value="{{ $value }}"
                                                @if (isset($editUser) && $editUser->custom_fields_data['field_' . $field->id] == $value) checked @elseif($key == 0) checked @endif>>
                                            <label
                                                for="optionsRadios{{ $key . $field->id }}">{{ $value }}</label>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @elseif($field->type == 'select')
                            {!! Form::select(
                                'custom_fields_data[' . $field->name . '_' . $field->id . ']',
                                $field->values,
                                isset($editUser) ? $editUser->custom_fields_data['field_' . $field->id] : '',
                                ['class' => 'form-control gender'],
                            ) !!}
                        @elseif($field->type == 'checkbox')
                            <div class="mt-checkbox-inline custom-checkbox checkbox-{{ $field->id }}">
                                <input type="hidden"
                                    name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                    id="{{ $field->name . '_' . $field->id }}" value=" ">
                                @foreach ($field->values as $key => $value)
                                    <label class="mt-checkbox mt-checkbox-outline">
                                        <input name="{{ $field->name . '_' . $field->id }}[]" type="checkbox"
                                            onchange="checkboxChange('checkbox-{{ $field->id }}', '{{ $field->name . '_' . $field->id }}')"
                                            value="{{ $value }}"> {{ $value }}
                                        <span></span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif($field->type == 'date')
                            <input type="text" class="form-control date-picker" size="16"
                                name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                value="{{ isset($editUser->dob) ? Carbon\Carbon::parse($editUser->dob)->format('Y-m-d') : Carbon\Carbon::now()->format($global->date_format) }}">
                        @endif
                        <div class="form-control-focus"> </div>
                        <span class="help-block"></span>

                    </div>
                </div>
            @endforeach
        @endif

    </div>
@endif

<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

<script>
    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());

        });
    $("#add_event").hide();
    $("#menu-switch").on("change", function() {
        if ($(this).is(':checked')) {
            $("#add_event").show();
        
        } else {
            $("#add_event").hide();
           
        }
    })
</script>
<script>
    $(".select2").select2({
        formatNoMatches: function() {
            return "No record found.";
        }
    });
</script>

