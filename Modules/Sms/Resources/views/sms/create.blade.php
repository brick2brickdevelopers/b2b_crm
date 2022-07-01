@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ __($pageIcon) }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">SMS {{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <style>
        .sweet-alert {
            width: 50% !important;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                

                <div class="vtabs customvtab">
                    @include('sections.super_admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="alert alert-info col-md-12">
                                    <p>
                                        <i class="fa fa-info-circle"></i> @lang('sms::modules.gatewayLimitation')
                                    </p>
                             
                                </div>

                                <div class="col-md-12">
                             
                                    <div class="row" id="smtp-container">
                                        <div class="col-sm-12 col-xs-12 p-t-20">

                                            {!! Form::open(['id'=>'createTypes','class'=>'ajax-form','method'=>'POST']) !!}

                                            <input type="hidden"
                                            @if ($smsSetting->whatsapp_status || $smsSetting->status)
                                                value="twilio"
                                            @elseif ($smsSetting->nexmo_status)
                                                value="nexmo"
                                            @elseif ($smsSetting->msg91_status)
                                                value="msg91"
                                            @endif
                                            name="active_gateway" id="active_gateway">

                                            <div class="form-body">
                                                
                                                <div class="row m-b-20">
                                                    <div class="col-xs-10">
                                                        <img src="{{ asset('img/twilio-logo-red.6b0811b1f.svg') }}" height="35" alt="">
                                                    </div>    
                                                    <div class="col-xs-2 text-right">
                                                        <div class="switchery-demo">
                                                            <input type="checkbox"
                                                                @if ($smsSetting->whatsapp_status || $smsSetting->status)
                                                                    checked
                                                                @endif
                                                                id="twilio-gateway"
                                                                class="js-switch  sms-gateway-status"
                                                                data-color="#99d683"
                                                                data-secondary-color="#ff0000"
                                                                data-gateway="twilio"
                                                                value="twilio"
                                                                />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row sms-gateway-form" id="twilio-form" 
                                                    @if (!$smsSetting->whatsapp_status && !$smsSetting->status)
                                                        style="display: none"
                                                    @endif
                                                    >
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Account SID</label>
                                                            <input type="password" name="account_sid" id="account_sid" class="form-control" value="{{ $smsSetting->account_sid }}">
                                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                        </div>
                                                    </div>
        
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Auth Token</label>
                                                            <input type="password" name="auth_token" id="auth_token" class="form-control" value="{{ $smsSetting->auth_token }}">
                                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label>SMS @lang('sms::app.fromNumber')</label>
                                                        <div class="form-group">
                                                            <input type="tel" name="from_number" id="from_number" class="form-control" 
                                                            
                                                            autocomplete="off" value="{{ $smsSetting->from_number }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="m-b-10">
                                                                <label><img src="{{ asset("img/whatsapp.svg") }}" height="25" alt=""> WhatsApp</label>
                                                            </div>
                                                            <div class="radio radio-inline">
                                                                <input type="radio" @if ($smsSetting->whatsapp_status == 1 || is_null($smsSetting->whatsapp_status)) checked @endif name="whatsapp_status" id="whatsapp_status1" value="1">
                                                                <label for="whatsapp_status1" class="">
                                                                    @lang('app.enable') </label>
                    
                                                            </div>
                                                            <div class="radio radio-inline ">
                                                                <input type="radio" @if ($smsSetting->whatsapp_status == 0) checked @endif name="whatsapp_status"
                                                                       id="whatsapp_status2" value="0">
                                                                <label for="whatsapp_status2" class="">
                                                                    @lang('app.disable') </label>
                                                            </div>
                                                        </div>
                                                    </div>
        

                                                    <div class="col-md-6">
                                                        <label>WhatsApp @lang('sms::app.fromNumber')</label>
                                                        <div class="form-group">
                                                            <input type="tel" name="whatapp_from_number" id="whatapp_from_number" class="form-control"
                                                            @if ($smsSetting->whatsapp_status == 0) readonly @endif
                                                            autocomplete="nope" value="{{ $smsSetting->whatapp_from_number }}">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="row m-b-20 m-t-30">
                                                    <div class="col-xs-10">
                                                        <img src="{{ asset('img/vonage-nexmo.png') }}" height="35" alt="">
                                                    </div>    
                                                    <div class="col-xs-2 text-right">
                                                        <div class="switchery-demo">
                                                            <input type="checkbox"
                                                                @if ($smsSetting->nexmo_status)
                                                                    checked
                                                                @endif

                                                                id="nexmo-gateway"
                                                                class="js-switch  sms-gateway-status"
                                                                data-color="#99d683"
                                                                data-secondary-color="#ff0000"
                                                                data-gateway="nexmo"
                                                                value="nexmo"
                                                                 />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row sms-gateway-form" id="nexmo-form" 
                                                @if (!$smsSetting->nexmo_status) style="display: none" @endif>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>API Key</label>
                                                            <input type="password" name="nexmo_api_key" id="nexmo_api_key" class="form-control" value="{{ $smsSetting->nexmo_api_key }}">
                                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                        </div>
                                                    </div>
        
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>API Secret</label>
                                                            <input type="password" name="nexmo_api_secret" id="nexmo_api_secret" class="form-control" value="{{ $smsSetting->nexmo_api_secret }}">
                                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                        </div>
                                                    </div>
        

                                                    <div class="col-md-12">
                                                        <label>SMS @lang('sms::app.fromNumber')</label>
                                                        <div class="form-group">
                                                            <input type="text" name="nexmo_from_number" id="nexmo_from_number" class="form-control" 
                                                            autocomplete="nope" value="{{ $smsSetting->nexmo_from_number }}">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="row m-b-20 m-t-30">
                                                    <div class="col-xs-10">
                                                        <img src="{{ asset('img/msg91_logo.svg') }}" height="35" alt="">
                                                    </div>    
                                                    <div class="col-xs-2 text-right">
                                                        <div class="switchery-demo">
                                                            <input type="checkbox"
                                                                @if ($smsSetting->msg91_status)
                                                                    checked
                                                                @endif

                                                                id="msg91-gateway"
                                                                class="js-switch  sms-gateway-status"
                                                                data-color="#99d683"
                                                                data-secondary-color="#ff0000"
                                                                data-gateway="msg91"
                                                                value="msg91"
                                                                 />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row sms-gateway-form" id="msg91-form" 
                                                @if (!$smsSetting->msg91_status) style="display: none" @endif>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>AUTH KEY</label>
                                                            <input type="password" name="msg91_auth_key" id="msg91_auth_key" class="form-control" value="{{ $smsSetting->msg91_auth_key }}">
                                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>SENDER ID</label>
                                                            <input type="text" name="msg91_from" id="msg91_from" class="form-control" value="{{ $smsSetting->msg91_from }}">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-actions">
                                                            <button type="submit" id="save-type" class="btn btn-success"><i
                                                                        class="fa fa-check"></i> @lang('app.save')
                                                            </button>

                                                            <button type="button" id="send-test-email"
                                                                class="btn btn-primary">@lang('sms::modules.sendTestMessage')</button>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                </div>
                                <!-- .row -->

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="testMailModal" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Test Message</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['id'=>'testEmail','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12 m-b-20">
                                <label>Enter mobile number where test message needs to be sent</label>
                                <div class="form-group">
                                    <select class="select2 phone_country_code form-control" name="phone_code">
                                        @foreach ($countries as $item)
                                            <option
                                            @if ($user->country_id == $item->id)
                                                selected
                                            @endif
                                             value="+{{ $item->phonecode }}">+{{ $item->phonecode.' ('.$item->iso.')' }}</option>
                                        @endforeach
                                    </select>
                                    <input type="tel" name="mobile" id="mobile" class="mobile" value="{{ $user->mobile }}">
                                </div>
                                
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn default  btn-sm" data-dismiss="modal">@lang('app.close')</button>
                        <button type="button" class="btn btn-success btn-sm" id="send-test-email-submit">@lang('app.submit')</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->.
        </div>

    </div> 
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>

<script type="text/javascript">
    'use strict';

    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function () {
        new Switchery($(this)[0], $(this).data());
    });


    $('#twilio-gateway, #nexmo-gateway, #msg91-gateway').change(function () {
        var gateway = $(this).data('gateway');

        $('#active_gateway').val('')
        if ($(this).is(':checked')) {
            $('#'+gateway+'-form').show();

            $('.sms-gateway-status').each(function (index) {
                var switchStatus = $('.sms-gateway-status')[index].value;
                var switchChecked = $('.sms-gateway-status')[index].checked;
                if (gateway != switchStatus && switchChecked) {
                    $(this).trigger('click');
                }
            });
            $('#active_gateway').val(gateway)
            
        } else {
            $('#'+gateway+'-form').hide();
        }

    })


    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('.change-email-setting').change(function () {
        var id = $(this).data('setting-id');

        if ($(this).is(':checked'))
            var sendEmail = 'yes';
        else
            var sendEmail = 'no';

        var url = '{{route('super-admin.sms.update', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "POST",
            data: {'id': id, 'send_email': sendEmail, '_method': 'PUT', '_token': '{{ csrf_token() }}'}
        })
    });


    //    save project members
    $("body").on('click', '#save-type', function() {
        $.easyAjax({
            url: '{{route('super-admin.sms.store')}}',
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

    $('input[name="status"]').change(function() {
        let status = $(this).val();
        if (status == "0") {
            $("#from_number").attr("readonly", true);
        } else{
            $("#from_number").removeAttr("readonly");
        }
    })

    $('input[name="whatsapp_status"]').change(function() {
        let status = $(this).val();
        if (status == "0") {
            $("#whatapp_from_number").attr("readonly", true);
        } else{
            $("#whatapp_from_number").removeAttr("readonly");
        }
    })

    $("body").on('click', '#send-test-email', function() {
        $('#testMailModal').modal('show')
    });

    $("body").on('click', '#send-test-email-submit', function() {
        $.easyAjax({
            url: '{{route('super-admin.sms.sendTestMessage')}}',
            type: "GET",
            messagePosition: "inline",
            container: "#testEmail",
            data: $('#testEmail').serialize(),

        })
    });


</script>


@endpush

