@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
    <link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('restapi::app.androidRestAPISettings')</div>

                <div class="vtabs customvtab">
                    @include('sections.super_admin_setting_menu')

                    <div class="row m-t-10">
                        <div class="white-box">
                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label required">@lang('restapi::app.fcmKey')</label>
                                    <input
                                        type="password"
                                        value="{{ $restAPISetting->fcm_key }}"
                                        min="0"
                                        onfocus="this.removeAttribute('readonly')"
                                        class="form-control auto-complete-off"
                                        name="fcm_key"
                                        id="fcm_key" />
                                    <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <button class="btn btn-success" id="save-form" type="button">@lang('app.save')</button>
                            </div>

                            {!! Form::close() !!}
                        </div>
{{--                        @if($restAPISetting->fcm_key)--}}
{{--                            <div class="white-box">--}}
{{--                                <div class="col-md-12 m-t-10">--}}
{{--                                    <label class="control-label required">--}}
{{--                                        @lang('restapi::app.testAndroidPushNotification')--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-12 m-t-10">--}}
{{--                                    <button type="button" onclick="testPush('android')" class="btn btn-primary">--}}
{{--                                        @lang('restapi::app.sendTestPushNotification')--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    </div>
                    <!-- /.row -->
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12 m-t-10 m-b-10">--}}
{{--                            <div class="panel-heading">@lang('restapi::app.applicationSetting')</div>--}}
{{--                        </div>--}}
{{--                        <div class="white-box">--}}
{{--                            <div class="m-b-10">--}}
{{--                                <button id="add-application" class="btn btn-success btn-sm btn-outline">--}}
{{--                                    <i class="fa fa-plus"></i>--}}
{{--                                    @lang('restapi::modules.applicationSettings.addApplication')--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                            <div id="vhome3" class="tab-pane active">--}}
{{--                                <div class="table-responsive">--}}
{{--                                    <table class="{{ $tableClass }}" id="application_table">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th>#</th>--}}
{{--                                            <th>@lang('app.name')</th>--}}
{{--                                            <th>@lang('app.status')</th>--}}
{{--                                            <th>@lang('app.action')</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                                <div class="clearfix"></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
{{--            <div class="panel panel-inverse">--}}
{{--                <div class="panel-heading">@lang('restapi::app.iosRestAPISettings')</div>--}}
{{--            </div>--}}
            <div class="white-box">
{{--                @if($iosNotificationWarning)--}}
{{--                    <div class="alert alert-info">--}}
{{--                        <h5 class="text-white">--}}
{{--                            <b>{{env('APN_PEM', 'aps.pem')}}</b> file not uploaded for ios push notifications. Upload--}}
{{--                            <b>{{env('APN_PEM', 'aps.pem')}}</b> file to the root where app, routes,--}}
{{--                            resources folder exists. Click here to know how to generate--}}
{{--                            <b>{{env('APN_PEM', 'aps.pem')}}</b> file--}}
{{--                        </h5>--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12 m-t-10">--}}
{{--                            <label class="control-label required">--}}
{{--                                @lang('restapi::app.testIOSPushNotification')--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-12 m-t-10">--}}
{{--                            <button type="button" onclick="testPush('ios')" class="btn btn-primary">--}}
{{--                                @lang('restapi::app.sendTestPushNotification')--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
            </div>
        </div>
    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div
        class="modal fade bs-modal-md in"
        id="projectCategoryModal"
        role="dialog"
        aria-labelledby="myModalLabel"
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
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}

    {{--show secret Ajax Modal--}}
    <div
            class="modal fade bs-modal-md in"
            id="showSecretModal"
            role="dialog"
            aria-labelledby="myModalLabel"
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
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')

    <script>
        // change task Setting For Setting
        $('#save-form').click(function () {

            $.easyAjax({
                url: '{{route('super-admin.rest-api-setting.update', $restAPISetting->id)}}',
                container: '#editSettings',
                type: "POST",
                data: $('#editSettings').serialize()
            })

        });
        {{--function testPush(platform){--}}
        {{--    $('#projectCategoryModal').modal('hide');--}}
        {{--    var url = "{{ route('super-admin.rest-api.test-push',':platform') }}";--}}
        {{--    url = url.replace(':platform', platform);--}}
        {{--    $('#projectCategoryModal #modelHeading').html('...');--}}
        {{--    $.ajaxModal('#projectCategoryModal', url);--}}
        {{--    return false;--}}
        {{--}--}}

        {{--function sendPush(){--}}
        {{--    var url = '{{route('super-admin.rest-api.send-push')}}';--}}

        {{--    $.easyAjax({--}}
        {{--        url: url,--}}
        {{--        type: "POST",--}}
        {{--        container: '#sendPush',--}}
        {{--        data: $('#sendPush').serialize(),--}}
        {{--        success: function (response) {--}}
        {{--            if (response.status == 'success') {--}}
        {{--                $('#projectCategoryModal').modal('hide');--}}
        {{--            }--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}

    </script>
{{--    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>--}}
{{--    <script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>--}}
{{--    <script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>--}}
{{--    <script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>--}}

{{--    <script src="{{ asset('plugins/bower_components/jquery.repeater/jquery.repeater.js') }}"></script>--}}
{{--    <script>--}}
{{--        $(function() {--}}
{{--            var table = $('#application_table').dataTable({--}}
{{--                responsive: true,--}}
{{--                processing: true,--}}
{{--                serverSide: true,--}}
{{--                ajax: '{!! route('super-admin.application-setting.index') !!}',--}}
{{--                "order": [[ 0, "desc" ]],--}}
{{--                deferRender: true,--}}
{{--                language: {--}}
{{--                    "url": "<?php echo __("app.datatable") ?>"--}}
{{--                },--}}
{{--                "fnDrawCallback": function( oSettings ) {--}}
{{--                    $("body").tooltip({--}}
{{--                        selector: '[data-toggle="tooltip"]'--}}
{{--                    });--}}
{{--                },--}}
{{--                columns: [--}}
{{--                    {data: 'id', name: 'id', orderable: false, searchable: false, visible:false},--}}
{{--                    {data: 'name', name: 'name', orderable: true, searchable: true},--}}
{{--                    {data: 'status', name: 'status', orderable: true, searchable: true},--}}
{{--                    {data: 'action', name: 'action', orderable: false, searchable: false, width: "100px"}--}}
{{--                ]--}}
{{--            });--}}

{{--            $('body').on('click', '.sa-params', function(){--}}
{{--                var id = $(this).data('application-id');--}}
{{--                swal({--}}
{{--                    title: "@lang('messages.sweetAlertTitle')",--}}
{{--                    text: "@lang('messages.deleteField')",--}}
{{--                    type: "warning",--}}
{{--                    showCancelButton: true,--}}
{{--                    confirmButtonColor: "#DD6B55",--}}
{{--                    confirmButtonText: "@lang('messages.confirmDelete')",--}}
{{--                    cancelButtonText: "@lang('messages.confirmNoArchive')",--}}
{{--                    closeOnConfirm: true,--}}
{{--                    closeOnCancel: true--}}
{{--                }, function(isConfirm){--}}
{{--                    if (isConfirm) {--}}

{{--                        var url = "{{ route('super-admin.application-setting.destroy',':id') }}";--}}
{{--                        url = url.replace(':id', id);--}}

{{--                        var token = "{{ csrf_token() }}";--}}

{{--                        $.easyAjax({--}}
{{--                            type: 'POST',--}}
{{--                            url: url,--}}
{{--                            data: {'_token': token, '_method': 'DELETE'},--}}
{{--                            success: function (response) {--}}
{{--                                if (response.status === "success") {--}}
{{--                                    $.unblockUI();--}}
{{--//                                    swal("Deleted!", response.message, "success");--}}
{{--                                    table._fnDraw();--}}
{{--                                }--}}
{{--                            }--}}
{{--                        });--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}

{{--            $('#add-application').click(function(){--}}
{{--                var url = '{{ route('super-admin.application-setting.create')}}';--}}
{{--                $('#modelHeading').html('...');--}}
{{--                $.ajaxModal('#projectCategoryModal',url);--}}
{{--                return false;--}}
{{--            })--}}

{{--            $('body').on('click', '.edit-application', function() {--}}
{{--                var id = $(this).data('application-id');--}}
{{--                var url = "{{ route('super-admin.application-setting.edit',':id') }}";--}}
{{--                url = url.replace(':id', id);--}}
{{--                $('#projectCategoryModal #modelHeading').html('...');--}}
{{--                $.ajaxModal('#projectCategoryModal',url);--}}
{{--                return false;--}}
{{--            })--}}

{{--        });--}}
{{--        function showSecret(id){--}}
{{--            $('#projectCategoryModal').modal('hide');--}}
{{--            var url = "{{ route('super-admin.application-setting.regenerate-secret',':id') }}";--}}
{{--            url = url.replace(':id', id);--}}
{{--            $('#showSecretModal #modelHeading').html('...');--}}
{{--            $.ajaxModal('#showSecretModal',url);--}}
{{--            return false;--}}
{{--        }--}}
{{--    </script>--}}

@endpush