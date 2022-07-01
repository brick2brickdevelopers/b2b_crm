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
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">

@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('payroll::modules.payroll.salaryComponents')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('payroll::sections.payroll_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="white-box">
                                        <h3>@lang('app.addNew')</h3>

                                        {!! Form::open(['id'=>'createTypes','class'=>'ajax-form','method'=>'POST']) !!}

                                        <div class="form-body">

                                            <div class="row">
    
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('payroll::modules.payroll.componentName')</label>
                                                        <input type="text" name="component_name" id="component_name" class="form-control">
                                                    </div>
                                                </div>
    
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('payroll::modules.payroll.componentType')</label>
                                                        <select name="component_type" id="component_type" class="form-control">
                                                            <option value="earning">@lang('payroll::modules.payroll.earning')</option>
                                                            <option value="deduction">@lang('payroll::modules.payroll.deduction')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('payroll::modules.payroll.valueType')</label>
                                                        <select name="value_type" id="value_type" class="form-control">
                                                            <option value="fixed">@lang('payroll::modules.payroll.fixed')</option>
                                                            <option value="percent">@lang('payroll::modules.payroll.salaryPercent')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>@lang('payroll::modules.payroll.componentValue') (@lang('app.monthly'))</label>
                                                        <input type="text" name="component_value" id="component_value" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>@lang('payroll::modules.payroll.componentValue') (@lang('payroll::app.menu.weekly'))</label>
                                                        <input type="text" name="weekly_value" value="0" id="weekly_value" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>@lang('payroll::modules.payroll.componentValue') (@lang('payroll::app.menu.biweekly'))</label>
                                                        <input type="text" name="biweekly_value" value="0" id="biweekly_value" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>@lang('payroll::modules.payroll.componentValue')  (@lang('payroll::app.menu.semimonthly'))</label>
                                                        <input type="text" name="semimonthly_value" value="0" id="semimonthly_value" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-actions">
                                                        <button type="submit" id="save-type" class="btn btn-success"><i
                                                                    class="fa fa-check"></i> @lang('app.save')
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {!! Form::close() !!}

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="white-box">
                                        <h3>@lang('payroll::modules.payroll.salaryComponents')</h3>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5><i class="fa fa-sliders"></i> @lang('app.filterBy')</h5>
                                            </div>
                                            <form action="" id="filter-form">
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('payroll::modules.payroll.componentType')</label>
                                                        <select name="component_type" id="component_type_filter" class="form-control">
                                                            <option value="all">--</option>
                                                            <option value="earning">@lang('payroll::modules.payroll.earning')</option>
                                                            <option value="deduction">@lang('payroll::modules.payroll.deduction')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('payroll::modules.payroll.valueType')</label>
                                                        <select name="value_type" id="value_type_filter" class="form-control">
                                                            <option value="all">--</option>
                                                            <option value="fixed">@lang('payroll::modules.payroll.fixed')</option>
                                                            <option value="percent">@lang('payroll::modules.payroll.salaryPercent')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label col-xs-12">&nbsp;</label>
                                                        <button type="button" id="apply-filters" class="btn btn-success btn-sm col-md-6"><i class="fa fa-check"></i> @lang('app.apply')</button>
                                                        <button type="button" id="reset-filters" class="btn btn-inverse btn-sm  col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('payroll::modules.payroll.componentName')</th>
                                                    <th>@lang('payroll::modules.payroll.componentType')</th>
                                                    <th>@lang('payroll::modules.payroll.componentValue')</th>
                                                    <th>@lang('payroll::modules.payroll.valueType')</th>
                                                    <th>@lang('app.action')</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
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
    <!-- .row -->


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="ticketTypeModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
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
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">


    //    save project members
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.salary-components.store')}}',
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


    $('body').on('click', '.delete-type', function () {
        var id = $(this).data('type-id');
        swal({
            title: "Are you sure?",
            text: "This will remove the component from the list.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.salary-components.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            loadTable();
                            // window.location.reload();
                        }
                    }
                });
            }
        });
    });


    $('body').on('click', '.edit-type', function () {
        var typeId = $(this).data('type-id');
        var url = '{{ route("admin.salary-components.edit", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.tickets.ticketType') }}");
        $.ajaxModal('#ticketTypeModal', url);
    })

    function loadTable() {
        var component_type_filter = $('#component_type_filter').val();
        var value_type_filter = $('#value_type_filter').val();

        table = $('#users-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            destroy: true,
            stateSave: true,
            ajax: '{!! route('admin.salary-components.data') !!}?component_type_filter=' + component_type_filter + '&value_type_filter=' + value_type_filter,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            columns: [
                { data: 'DT_RowIndex', orderable: true, searchable: false },
                {data: 'component_name', name: 'component_name'},
                {data: 'component_type', name: 'component_type'},
                {data: 'component_value', name: 'component_value'},
                {data: 'value_type', name: 'value_type'},
                {data: 'action', name: 'action'}
            ]
        })
    }

    loadTable();

    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })

    $('#apply-filters').click(function () {
        loadTable();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        loadTable();
    })


</script>


@endpush

