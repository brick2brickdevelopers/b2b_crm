@extends('layouts.app')


@php
    // dd(json_decode($call_flow_diagram->extensions)->num)
    $callflow = json_decode($call_flow_diagram->extensions);
    // $key = 1;
//    dd($callflow->department['2']);
//  dd($callflow);
@endphp

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.teams.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
    <link rel="stylesheet"
        href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/tagify-master/dist/tagify.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.add') Call Flow Design</div>
                <p class="text-muted  font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id' => 'updateCallFlowDiagram', 'class' => 'ajax-form', 'method' => 'POST']) !!}

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="greeting" class="required">Call Flow Design Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            autocomplete="nope" value="{{ $call_flow_diagram->name }}" required>
                                    </div>
                                </div>
                                @php 
                                    $extension = json_decode($call_flow_diagram->extensions,true);

                                  
                                @endphp

                             
                             
                                {{-- {{ $call_flow_diagram->extensions }} --}}
                                {{-- {{ in_array($item->id, $extension) ? 'selected' : '' }} --}}
                                <div class="form-group">
                                    <div class="row">
                                            <label for="menu" class="required">
                                                Do you need Lead Greeting?
                                            </label>
                                            <div class="switchery-demo">
                                            <input id="lead_gretting" name="lead_gretting" type="checkbox"
                                                class="js-switch" data-size="small" data-color="#00c292" value="1" {{ !empty($call_flow_diagram->greetings_id) ? 'checked' :'' }}/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="greeting2">
                                    <label for="greeting">Leed Greeting</label>
                                    <div >
                                        <select class="select2 select2-multiple form-control" id="greetings_id"
                                            name="greetings_id" data-placeholder="Choose Greetings ...">
                                            <option value="">----</option>

                                            @foreach ($grettings as $item)
                                                <option value="{{ $item->id }}" {{ $call_flow_diagram->greetings_id==$item->id ? 'selected' :'' }}>{{ $item->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                               
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"> Did Number<span style="color:red">*</span></label>
                                        <div class="form-group">
                                            <select name="did_number"  class="form-control">
                                                @foreach($did_numbers as $did_number)
                                                     <option value="{{ $did_number->number }}" {{ $call_flow_diagram->did_number==$did_number->number ? 'selected' :'' }}>{{ $did_number->number }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="menu" class="required">Need a menu</label>
                                  
                                    
                                     
                                    <div class="switchery-demo">
                                        <input id="menu-switch" name="menu" type="checkbox" class="js-switch" 
                                            data-size="small" data-color="#00c292" value="1" {{$call_flow_diagram->menu == '1' ? 'checked' : ''}}/>
                                            <input type="hidden" class="menu_show" value="{{ $call_flow_diagram->menu }}"> 
                                    </div>
                                </div>
                                <div class="form-group menu-message">
                                    <label for="menu_message" class="required">Menu message</label>
                                    <div id="menu_message">
                                        <select class="select2 select2-multiple form-control" id="menu_message"
                                            name="menu_message" data-placeholder="Choose Menu ...">

                                            @foreach ($grettings as $item)
                                                <option value="{{ $item->id }}"  >{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group extension-directory" id="directory">
                                    <label for="menu_message" class="required">Department Directory</label>
                                    <div id="extension">
                                        <select class="select2 select2-multiple" multiple="multiple" id="extension"
                                            name="extensions[]" data-placeholder="Choose Department ...">
                                            @foreach (digits() as $item)
                                            <option value="{{ $item['id'] }}" {{in_array($item['id'] , $extension) ? 'selected' : ''}}>{{ $item['label'] }}
                                            </option>
                                        @endforeach

                                        </select>
                                    </div>

                                </div>

                                <div class="form-group extension-directory-with-number">


                                    @if(!empty($callflow->num))
                                    @forelse ($callflow->num as $key=>$itemx)
                                    <div class="row  department-choose" style="margin-bottom: 10px;">
                                        <div class="col-md-3 ">
                                            <label for="extension_directory_with_number" class="required">Extension
                                                Number</label>
                                            <div id="extension-directory-with-number">
                                                <select class="form-control" id="selectNumber" name="num[]">
                                                    @foreach (digits() as $item)
                                                        <option value="{{ $item['id'] }}" {{$itemx===  $item['label']?'selected':''}}>{{ $item['label'] }}  </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class=" col-md-6 appendHere"></div> --}}
                                        <div class="col-md-6">
                                            <label for="extension_directory_with_number" class="required">Department
                                                Directory</label>
                                            <div id="">
                                                <select class="form-control" id="selectGretting" name="voice[]"
                                                    data-placeholder="Choose Department ...">
                                                    {{-- @foreach ($grettings as $item)
                                                        <option value="{{ $item->id }}" {{$callflow->ext[$key]===  "$item->id"?'selected':''}} >{{ $item->name }}</option>
                                                    @endforeach --}}
                                                   
                                                    @foreach ($departments as $item)
                                                    {{-- @php $key =$loop->index ; @endphp  --}}
                                                    <option value="{{ $item->id }}" {{$callflow->department[$key]== $item->id?'selected':''}}>{{ $item->team_name }}</option>
                                                     @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 deleteBtn" style="margin-top: 21px;" id="deleteBtn">
                                            <button class="btn btn-md btn-primary" type="button"
                                                onclick="removeDepartment($(this))">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>



                                    </div>
                                    @empty
                                    <div class="row  department-choose" style="margin-bottom: 10px;">
                                        <div class="col-md-3 ">
                                            <label for="extension_directory_with_number" class="required">Extension
                                                Number</label>
                                            <div id="extension-directory-with-number">
                                                <select class="form-control" id="selectNumber" name="num[]">
                                                    @foreach (digits() as $item)
                                                        <option value="{{ $item['id'] }}" >{{ $item['label'] }}  </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class=" col-md-6 appendHere"></div> --}}
                                        <div class="col-md-6">
                                            <label for="extension_directory_with_number" class="required">Departments
                                                Directory</label>
                                            <div id="">
                                                <select class="form-control" id="selectGretting" name="voice[]"
                                                    data-placeholder="Choose Department ...">
                                                    @foreach ($departments as $item)
                                                    <option value="{{ $item->id }}">{{ $item->team_name }}</option>
                                                @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 deleteBtn" style="margin-top: 21px;" id="deleteBtn">
                                            <button class="btn btn-md btn-primary" type="button"
                                                onclick="removeDepartment($(this))">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforelse
                                    @endif
                                  
                                    <button class="btn btn-md btn-primary" id="addBtn" type="button">
                                        Add new Row
                                    </button>
                                </div>


                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="menu" class="required">Do you need voicemail with each department
                                            ?</label>

                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-check-input" name="voicemail" type="checkbox" id="inline"
                                            value="option2" value="1" {{$call_flow_diagram->voicemail == '1' ? 'checked' : ''}}/>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="menu" class="required">Do you want call flow for non working
                                    hours?</label>
                                <div class="switchery-demo">
                                    <input id="menu-switch_work_hour" name="non_working_hours" type="checkbox"
                                        class="js-switch" data-size="small" data-color="#00c292" value="1" {{$call_flow_diagram->non_working_hours == '1' ? 'checked' : ''}}/>
                                </div>
                            </div>

                            <div class="menu-switch_work_hour_row">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Set Time</label>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="">
                                                <input class="form-control" id="start_time" name="start_time"
                                                    data-placeholder="Start" value="{{$call_flow_diagram->start_time}}"/>



                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="">
                                                <input class="form-control" id="end_time" name="end_time"
                                                    data-placeholder="End" value="{{$call_flow_diagram->end_time}}"/>



                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="greeting" class="required">Welcome greeting</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="greeting">
                                                <select class="form-control" id="non_working_hours_greetings"
                                                    name="non_working_hours_greetings"
                                                    data-placeholder="Choose Greetings ...">
                                                    @foreach ($grettings as $item)
                                                        <option value="{{ $item->id }}" {{$call_flow_diagram->non_working_hours_greetings == $item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="greeting" class="required">Voicemail message</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="greeting">
                                                <select class="form-control" id="non_working_hours_voicemail"
                                                    name="non_working_hours_voicemail"
                                                    data-placeholder="Choose Voicemail ...">
                                                    @foreach ($voicemails as $item)
                                                        <option value="{{ $item->id }}" {{$call_flow_diagram->non_working_hours_voicemail == $item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="menu" class="required">Do you want call flow for non working days?</label>
                                <div class="switchery-demo">
                                    <input id="menu-switch_work_day" name="non_working_days" type="checkbox"
                                        class="js-switch" data-size="small" data-color="#00c292" value="1" {{$call_flow_diagram->non_working_days == '1' ? 'checked' : ''}}/>
                                </div>
                            </div>
                           

                            <div class="menu-switch_work_day_row">
                                <div class="form-group">
                                    <div class="row">
                                        @php 
                                            $days = json_decode($call_flow_diagram->days,true);
                                        @endphp
                                       
                                        <div class="col-md-4">
                                            <label>Select Days</label>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" name='days[]' type="checkbox" id="inlineCheckbox2"
                                                    value="Mon" {{in_array("Mon", $days) ? 'checked' : ''}}>
                                                <label class="form-check-label"  for="inlineCheckbox2">Mon</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" name='days[]' type="checkbox" id="inlineCheckbox3"
                                                    value="Tue" {{in_array("Tue", $days) ? 'checked' : ''}}>
                                                <label class="form-check-label"  for="inlineCheckbox3">Tue</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" name='days[]' type="checkbox" id="inlineCheckbox2"
                                                    value="Wed" {{in_array("Wed", $days) ? 'checked' : ''}}>
                                                <label class="form-check-label" for="inlineCheckbox2">Wed</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" name='days[]' type="checkbox" id="inlineCheckbox3"
                                                    value="Thu" {{in_array("Thu", $days) ? 'checked' : ''}}>
                                                <label class="form-check-label" for="inlineCheckbox3">Thu</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" name='days[]' type="checkbox" id="inlineCheckbox2"
                                                    value="Fri" {{in_array("Fri", $days) ? 'checked' : ''}}>
                                                <label class="form-check-label" for="inlineCheckbox2">Fri</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" name='days[]' type="checkbox" id="inlineCheckbox3"
                                                    value="Sat" {{in_array("Sat", $days) ? 'checked' : ''}}>
                                                <label class="form-check-label" for="inlineCheckbox3">Sat</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" name='days[]' type="checkbox" id="inlineCheckbox2"
                                                    value="Sun" {{in_array("Sun", $days) ? 'checked' : ''}}> 
                                                <label class="form-check-label" for="inlineCheckbox2">Sun</label>
                                            </span>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="greeting" class="required">Welcome greeting</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="greeting">
                                                <select class="form-control" id="non_working_days_greetings"
                                                    name="non_working_days_greetings"
                                                    data-placeholder="Choose Greetings ...">
                                                    @foreach ($grettings as $item)
                                                        <option value="{{ $item->id }}" {{$call_flow_diagram->non_working_days_greetings == $item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="greeting" class="required">Voicemail message</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="greeting">
                                                <select class="form-control" id="non_working_days_voicemail"
                                                    name="non_working_days_voicemail"
                                                    data-placeholder="Choose Voicemail ...">
                                                    @foreach ($voicemails as $item)
                                                        <option value="{{ $item->id }}" {{$call_flow_diagram->non_working_days_voicemail == $item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                        <label for="menu" >
                                        Status                                            
                                        </label>
                                        <div class="switchery-demo">
                                        <input id="status" name="status" type="checkbox"
                                            class="js-switch" data-size="small" data-color="#00c292" value="1" {{$call_flow_diagram->status == '1' ? 'checked' : ''}}/>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="save-form"
                                class="btn btn-success waves-effect waves-light m-r-10">
                                @lang('app.save')
                            </button>
                            <a href="{{ route('admin.calling-group.index') }}"
                                class="btn btn-default waves-effect waves-light">@lang('app.back')</a>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- .row -->
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/tagify-master/dist/tagify.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <script>
        $("#start_time, #end_time").clockpicker({
            align: 'left',
            donetext: 'Done'
        });
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());

        });
    </script>
    <script>
        $(".select2").select2({
            formatNoMatches: function() {
                return "No record found.";
            }
        });
    </script>
    {{-- <script>
        var menu_show= $('.menu_show').val();
       if(menu_show==1){
                  $("#directory").hide();
                $(".menu-message").show();
                $(".extension-directory-with-number").show();
                $(".extension-directory").show()
       }
    </script> --}}
    <script>
        

        // $("#directory").hide();
        $(".menu-message").hide();
        $(".extension-directory-with-number").hide();
        $("#menu-switch").load("change", function() {
            if ($(this).is(':checked')) {
                $(".menu-message").show();
                $(".extension-directory-with-number").show();
                $(".extension-directory").hide()
            } else {
                $(".menu-message").hide();
                $(".extension-directory-with-number").hide();
                $(".extension-directory").show()
            }
            
        })

        
    </script>
    <script>
        $("#lead_gretting").load("change", function() {
            if ($(this).is(':checked')) {
                $("#greeting2").show();
            } else {
                $("#greeting2").hide();
            }
        })
        
    </script>
     <script>
        $("#greeting2").hide()
        $("#lead_gretting").on("change", function() {
            if ($(this).is(':checked')) {
                $("#greeting2").show();
            } else {
                $("#greeting2").hide();
            }
        })
        
    </script>

    <script>

$("#menu-switch").on("change", function() {
            if ($(this).is(':checked')) {
                $(".menu-message").show();
                $(".extension-directory-with-number").show();
                $(".extension-directory").hide()
            } else {
                $(".menu-message").hide();
                $(".extension-directory-with-number").hide();
                $(".extension-directory").show()
            }
            
        })
    </script>

<script>
    $('#save-form').click(function() {
        
    const url = "{{ route('admin.call-flow-design.update', ':xid') }}"
    const finalUrl = url.replace(':xid', "{{ $call_flow_diagram->id }}")
        $.easyAjax({
            url: finalUrl,
            container: '#updateCallFlowDiagram',
            type: "PUT",
            redirect: true,
            data: $('#updateCallFlowDiagram').serialize()
        })
    });
</script>

<script>
    $(".menu-switch_work_hour_row").hide()
    $("#menu-switch_work_hour").load("change", function() {
        if ($(this).is(':checked')) {
            $(".menu-switch_work_hour_row").show();
        } else {
            $(".menu-switch_work_hour_row").hide();
        }
    })
</script>
    <script>
        $(".menu-switch_work_hour_row").hide()
        $("#menu-switch_work_hour").on("change", function() {
            if ($(this).is(':checked')) {
                $(".menu-switch_work_hour_row").show();
            } else {
                $(".menu-switch_work_hour_row").hide();
            }
        })
    </script>
 <script>
    $(".menu-switch_work_day_row").hide()
    $("#menu-switch_work_day").load("change", function() {
        if ($(this).is(':checked')) {
            $(".menu-switch_work_day_row").show();
        } else {
            $(".menu-switch_work_day_row").hide();
        }
    })

</script>
    <script>
        $(".menu-switch_work_day_row").hide()
        $("#menu-switch_work_day").on("change", function() {
            if ($(this).is(':checked')) {
                $(".menu-switch_work_day_row").show();
            } else {
                $(".menu-switch_work_day_row").hide();
            }
        })

    </script>
    {{-- <script>
     $('#addBtn').click(function() {
            // var count = 10;
            // for (var i=0; i<count; i++){
                // $('div.appendHere').append('<div class="appendedDIVs">document.write(i)</div>');

                $('.appendHere').append(`<div class="col-md-6">
                                            <label for="extension_directory_with_number" class="required">Extension
                                                Directory</label>
                                            <div id="">
                                                <select class="select2 select2-multiple" multiple="multiple" id=""
                                                    name="" data-placeholder="Choose Department ...">


                                                </select>
                                            </div>
                                        </div>`);

                
          //  }
     })
</script> --}}



    <script>
        // deleteBtn = $('.deleteBtn').length;
        dep_number = $('.department-choose').length;
        $('.deleteBtn').hide()

        function add_menu_department() {
            var cloneData = $('.department-choose:first').clone()
            var selectNumber = 'selectNumber' + dep_number
            // cloneData.find('#selectNumber').attr("id", selectNumber);
            cloneData.find('#selectNumber').attr("name", "num[]");
            // cloneData.find('#selectNumber').attr("name", "num[" + dep_number + "]");
            // cloneData.find('#selectGretting').attr("name", "voice[" + dep_number + "]");
            cloneData.find('#selectGretting').attr("name", "voice[]");
            cloneData.find('#deleteBtn').removeClass("deleteBtn");
            $('.department-choose').find('#deleteBtn').css("display", "none");

            cloneData.find('#deleteBtn').css("display", "block");


            cloneData.insertAfter(".department-choose:last");
            dep_number = $('.department-choose').length;
            // deleteBtn = $('.deleteBtn').length;
            // $('.deleteBtn').length - 1

        }

        $('#addBtn').on('click', function() {
            add_menu_department()
        })

        function removeDepartment(e) {
            e.parent().parent().remove()
            dep_number = $('.department-choose').length;
            if (dep_number.length === 2) {} else {
                $('.department-choose:last').find('#deleteBtn').css("display", "block");

            }
        }
    </script>

@endpush
