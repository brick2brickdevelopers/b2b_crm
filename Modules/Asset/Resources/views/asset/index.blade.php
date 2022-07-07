@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-8 col-md-5 col-sm-6 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ $pageTitle }}
                <span class="text-info b-l p-l-10 m-l-5">{{ $totalAssets }}</span> <span
                        class="font-12 text-muted m-l-5"> @lang('asset::app.totalAssets')</span>
            </h4>
        </div>

        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-4 col-sm-6 col-md-7 col-xs-12 text-right">
            <a href="{{ route('admin.assets.create') }}"
               class="btn btn-outline btn-success btn-sm">@lang('asset::app.addNewAsset') <i class="fa fa-plus"
                                                                                                  aria-hidden="true"></i></a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ $pageTitle }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet"
          href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>

        .dashboard-stats .white-box .list-inline {
            margin-bottom: 0;
        }

        .dashboard-stats .white-box {
            padding: 10px;
        }

        .dashboard-stats .white-box .box-title {
            font-size: 13px;
            text-transform: capitalize;
            font-weight: 300;
        }
        #assets-table_wrapper .dt-buttons{
            display: none !important;
        }

    </style>
@endpush

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="white-box">

                @section('filter-section')
                    <div class="row" id="ticket-filters">

                        <form action="" id="filter-form">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h5>@lang('asset::app.assetType')</h5>
                                    <select class="select2 form-control" name="asset_type_id" id="asset_type_id" data-style="form-control">
                                        <option value="all">@lang('modules.client.all')</option>
                                        @foreach($assetType as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h5>@lang('asset::app.employees')</h5>
                                    <select class="select2 form-control" name="user_id" id="user_id" data-style="form-control">
                                        <option value="all">@lang('modules.client.all')</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h5>@lang('asset::app.status')</h5>
                                    <select class="select2 form-control" name="status" id="status" data-style="form-control">
                                        <option value="all">@lang('modules.client.all')</option>
                                        @foreach($status as $type)
                                            <option value="{{ $type }}">{{ ucwords($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group p-t-10">
                                    <label class="control-label col-xs-12">&nbsp;</label>
                                    <button type="button" id="apply-filters" class="btn btn-success btn-sm col-md-6"><i
                                                class="fa fa-check"></i> @lang('app.apply')</button>
                                    <button type="button" id="reset-filters"
                                            class="btn btn-inverse col-md-5 btn-sm col-md-offset-1"><i
                                                class="fa fa-refresh"></i> @lang('app.reset')</button>
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
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="assetModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    {!! $dataTable->scripts() !!}
    <script>
        $(".select2").select2();

        jQuery('#date-range').datepicker({
            toggleActive: true,
            format: '{{ $global->date_picker_format }}',
            language: '{{ $global->locale }}',
            autoclose: true
        });
        var table;
        $(function () {
            $('body').on('click', '.sa-params', function () {
                var id = $(this).data('user-id');
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted assets!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel please!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {

                        var url = "{{ route('admin.assets.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.easyBlockUI('#assets-table');
                                    window.LaravelDataTables["assets-table"].draw();
                                    $.easyUnblockUI('#assets-table');
                                }
                            }
                        });
                    }
                });
            });

        });

        $('.toggle-filter').click(function () {
            $('#ticket-filters').toggle('slide');
        })

        $('#apply-filters').click(function () {
            $('#assets-table').on('preXhr.dt', function (e, settings, data) {
                var asset_type = $('#asset_type_id').val();
                var user_id = $('#user_id').val();
                var status = $('#status').val();
                data['asset_type'] = asset_type;
                data['user_id'] = user_id;
                data['status'] = status;


            });

            $.easyBlockUI('#assets-table');
            window.LaravelDataTables["assets-table"].draw();
            $.easyUnblockUI('#assets-table');

        });

        $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
            $('#status').val('all');
            $('.select2').val('all');
            $('#filter-form').find('select').select2();

            $.easyBlockUI('#assets-table');
            window.LaravelDataTables["assets-table"].draw();
            $.easyUnblockUI('#assets-table');
        })

        $('#assets-table').on('click', '.asset-name', function () {
            $(".right-sidebar").slideDown(50).addClass("shw-rside");

            var id = $(this).data('asset-id');
            var url = "{{ route('admin.assets.show',':id') }}";
            url = url.replace(':id', id);

            $.easyAjax({
                type: 'GET',
                url: url,
                success: function (response) {
                    if (response.status == "success") {
                        $('#right-sidebar-content').html(response.view);
                    }
                }
            });
        })
        function lend(id) {
            var url = '{{ route('admin.history.create', ':id')}}';
            url = url.replace(':id', id);
            $('#modelHeading').html("@lang('asset::app.lend')");
            $.ajaxModal('#assetModal', url);
        }

        function returnAsset(history_id, asset_id) {
            var url = '{{ route('admin.assets.return', [':asset', ':history'])}}';
            url = url.replace(':asset', asset_id);
            url = url.replace(':history', history_id);
            console.log(url, 'hello from return');
            $('#modelHeading').html("@lang('asset::app.return')");
            $.ajaxModal('#assetModal', url);
        }

    </script>
@endpush
