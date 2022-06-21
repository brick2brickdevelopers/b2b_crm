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
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="description">Email *</label>
                        <input class="form-control" {{ $lead ? 'readonly' : '' }}
                            value="{{ $lead ? $lead->email : '' }}" name="email" id="incoming_name"></input>
                    </div>
                </div>
                @if (!$lead)
                    <div class="col-lg-12">
                        <center><button type="submit" id="" class="btn btn-success btn-sm">SAVE</button></center>
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

                    <div class="col-lg-12" id="incoming_add_donation_div">

                    </div>
                    <div class="col-lg-12" id="incoming_add_prayer_request_div">

                    </div>

                    <div class="col-lg-12">
                        <center><button type="submit" id="" class="btn btn-success btn-sm">SAVE</button></center>
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
                url: "{{ route('member.leads.storeLoggedCallDetails') }}",
                type: "POST",
                data: $('#incoming_call_details_save').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        console.warn();
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
                                id="{{ $field->name }}"
                                cols="3">{{ $editUser->custom_fields_data['field_' . $field->id] ?? '' }}</textarea>
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
                            {!! Form::select('custom_fields_data[' . $field->name . '_' . $field->id . ']', $field->values, isset($editUser) ? $editUser->custom_fields_data['field_' . $field->id] : '', ['class' => 'form-control gender']) !!}
                        @elseif($field->type == 'checkbox')
                            <div class="mt-checkbox-inline custom-checkbox checkbox-{{ $field->id }}">
                                <input type="hidden" name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
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
