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
                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="alert alert-info col-md-12">
                                    <p>
                                        <i class="fa fa-info-circle"></i> @lang('sms::modules.mobileNumberFormat')
                                    </p>
                                </div>


                                <div class="col-md-12">

                                    <h3 class="box-title m-b-0">@lang("sms::app.notificationTitle")</h3>

                                    <p class="text-muted m-b-10 font-13">
                                        @lang("sms::app.notificationSubtitle")
                                    </p>

                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 p-t-20">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form form-horizontal','method'=>'PUT']) !!}

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[4]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[4]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.userRegistration")</label>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[5]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[5]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.employeeAssign")</label>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[6]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[6]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.newNotice")</label>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[7]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[7]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.taskAssign")</label>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[0]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[0]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.expenseAdded")</label>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[1]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[1]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.expenseMember")</label>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[2]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[2]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.expenseStatus")</label>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[3]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[3]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.ticketRequest")</label>
                                            </div>


                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[8]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[8]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.leaveRequest")</label>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                               @if($emailSettings[9]->send_twilio == 'yes') checked
                                                               @endif class="js-switch change-email-setting"
                                                               data-color="#99d683"
                                                               data-setting-id="{{ $emailSettings[9]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.taskComplete")</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox"
                                                                @if($emailSettings[10]->send_twilio == 'yes') checked
                                                                @endif class="js-switch change-email-setting"
                                                                data-color="#99d683"
                                                                data-setting-id="{{ $emailSettings[10]->id }}"/>
                                                    </div>
                                                </div>
                                                <label class="col-sm-10">@lang("modules.emailSettings.invoiceNotification")</label>

                                                
                                            </div>

                                          
                                            

                                            {!! Form::close() !!}
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


    </div>
    <!-- .row -->


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

        var url = '{{route('admin.sms.update', ':id')}}';
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
            url: '{{route('admin.sms.store')}}',
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



</script>


@endpush

