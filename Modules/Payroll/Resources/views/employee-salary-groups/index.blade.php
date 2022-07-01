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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
<style>
    .select2-selection__choice {
        background-color: white !important;
        padding: 4px 5px !important;
    }

    .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('payroll::modules.payroll.manageEmployees')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('payroll::sections.payroll_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="white-box">
                                        <h3>{{ ucwords($salaryGroup->group_name) }} @lang('payroll::modules.payroll.salaryGroup')</h3>

                                        {!! Form::open(['id'=>'createTypes','class'=>'ajax-form','method'=>'POST']) !!}
                                        
                                        {!! Form::hidden('salary_group_id', $salaryGroup->id) !!}

                                        <div class="form-body">

                                            <div class="row">
    
                                                <div class="col-md-12">
                                                    <div class="form-group" id="user_id">
                                                        <label>@lang('payroll::modules.payroll.assignEmployees')</label>
                                                        <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                                                                 name="user_id[]">
                                                           
                                                        </select>
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
                                        <h3 class="b-b">@lang('app.menu.employees')</h3>
                                            <div class="row">                                          
                                                <div class="col-xs-12 m-b-10">
                                                    <input type="text" placeholder="@lang('app.search')" autocomplete="off" id="search-employee" class="form-control">
                                                </div> 
                                            </div>

                                            <div id="salary-group-employees">

                                                @foreach ($salaryGroup->employees as $item)
                                                    <div class="col-md-3 m-b-5 p-r-10" >
                                                        <div class="row b-all employee-list h-50">

                                                            <div class="col-sm-3 col-xs-4 p-t-10">
                                                                <img src="{{ $item->user->image_url }}" alt="user" class="img-circle" width="30">
            
                                                            </div>
                                                            <div class="col-sm-7 col-xs-6 p-t-10 font-12">
                                                                {{ ucwords($item->user->name) }}
                                                            
                                                            </div>

                                                            <div class="col-sm-2 col-xs-2 p-t-15">
                                                                <a href="javascript:;" class="remove-employee" data-id="{{ $item->id }}"><i class="fa fa-trash text-danger"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>

<script type="text/javascript">

var data = [
    @foreach($employees as $emp)
      { id: {{ $emp->id }}, text: '<span class="select2-tag">{{ $emp->name }}</span><span class="label label-info m-l-5">{{ $emp->group_name }}</span>' },
    @endforeach
];
    
    $(".select2").select2({
      data: data,
      templateResult: function (d) { return $(d.text); },
      templateSelection: function (d) { return $(d.text); },
      
    })

    //    save project members
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.employee-salary-groups.store')}}',
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


    $('body').on('click', '.remove-employee', function () {
        var id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "This will remove the employee from the group.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.employee-salary-groups.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            var searchString = $('search-employee').val();
                            searchEmployees(searchString);
                            $.unblockUI();
                        }
                    }
                });
            }
        });
    });


    $('#search-employee').keyup(function () {
        var searchString = $(this).val();
        searchEmployees(searchString);
    });

    function searchEmployees(searchString) {
        var searchString = searchString;
        $.easyAjax({
            url: '{{route('admin.employee-salary-groups.data')}}',
            container: '#createTypes',
            type: "POST",
            data: {searchString: searchString, groupId: "{{$salaryGroup->id}}", _token: "{{ csrf_token() }}" },
            success: function (response) {
                if (response.status == "success") {
                    $('#salary-group-employees').html(response.view);
                    $.unblockUI();
                    
                }
            }
        })
    }

   
   
</script>


@endpush

