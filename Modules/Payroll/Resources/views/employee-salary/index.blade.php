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
                <li class="active">{{ $pageTitle }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush


@section('filter-section')                    
<div class="row m-b-15" id="ticket-filters">

    <form action="" id="filter-form">
        
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">@lang('app.designation')</label>
                <select class="form-control select2" name="designation" id="designation" data-style="form-control">
                    <option value="all">@lang('modules.client.all')</option>
                    @forelse($designations as $designation)
                        <option value="{{$designation->id}}">{{ ucfirst($designation->name) }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">@lang('app.department')</label>
                <select class="form-control select2" name="department" id="department" data-style="form-control">
                    <option value="all">@lang('modules.client.all')</option>
                    @forelse($departments as $department)
                        <option value="{{$department->id}}">{{ ucfirst($department->team_name) }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <button type="button" id="apply-filters" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.apply')</button>
                <button type="button" id="reset-filters" class="btn btn-inverse m-l-10"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
            </div>
        </div>
    </form>
</div>
@endsection


@section('content')

    <div class="row">

        <div class="col-md-12">
            <div class="white-box">
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('app.name')</th>
                            <th>@lang('payroll::app.menu.payroll') @lang('payroll::app.menu.cycle')</th>
                            <th>@lang('payroll::modules.payroll.salaryGroup')</th>
                            <th>@lang('payroll::app.allowPayrollGenerate') </th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                    </table>
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
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<style>
    .select2-container-multi .select2-choices .select2-search-choice {
        background: #ffffff !important;
    }
</style>
<script>

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    var table;

    $(function() {
        loadTable();

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted user!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.employees.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });

    });
    function loadTable(){

        var designation     = $('#designation').val();
        var department     = $('#department').val();

        table = $('#users-table').dataTable({
            "lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            responsive: true,
            processing: true,
            serverSide: true,
            destroy: true,
            stateSave: true,
            ajax: '{!! route('admin.employee-salary.data') !!}?designation=' + designation + '&department=' + department,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function( oSettings ) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'salary_cycle', name: 'salary_cycle' },
                { data: 'group_name', name: 'group_name' },
                { data: 'allow_generate_payroll', name: 'allow_generate_payroll' },
                { data: 'action', name: 'action' },
            ]
        });
    }

    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })

    $('#apply-filters').click(function () {
        loadTable();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('#status').val('all');
        $('.select2').val('all');
        $('#filter-form').find('select').select2();
        loadTable();
    })

    $('body').on('click', '.save-initial-salary', function () {
        var id = $(this).data('user-id');
        var amount = $('#initial-salary-'+id).val();
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: '{{route('admin.employee-salary.store')}}',
            type: "POST",
            data: {user_id: id, amount: amount, _token : token, type: 'initial'},
            success: function (response) {
                if(response.status == "success"){
                    loadTable();
                    $.unblockUI();
                }
            }
        })

    });
    $('body').on('change', '.payroll-cycle', function () {
        var id = $(this).data('user-id');
        var cycle = $(this).val();
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: '{{route('admin.employee-salary.payroll-cycle')}}',
            type: "POST",
            data: {user_id: id, cycle: cycle, _token : token},
            success: function (response) {
                if(response.status == "success"){
                    loadTable();
                    $.unblockUI();
                }
            }
        })

    });

    $('body').on('change', '.payroll-status', function () {
        var id = $(this).data('user-id');
        var status = $(this).val();
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: '{{route('admin.employee-salary.payroll-status')}}',
            type: "POST",
            data: {user_id: id, status: status, _token : token},
            success: function (response) {
                if(response.status == "success"){
                    loadTable();
                    $.unblockUI();
                }
            }
        })

    });

    $('body').on('click', '.update-salary', function () {
        var typeId = $(this).data('user-id');
        var url = '{{ route("admin.employee-salary.edit", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.tickets.ticketType') }}");
        $.ajaxModal('#ticketTypeModal', url);
    })
    
    $('body').on('click', '.salary-history', function () {
        var typeId = $(this).data('user-id');
        var url = '{{ route("admin.employee-salary.show", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.tickets.ticketType') }}");
        $.ajaxModal('#ticketTypeModal', url);
    })
    

</script>
@endpush