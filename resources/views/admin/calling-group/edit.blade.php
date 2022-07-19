@extends('layouts.app')

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
@endpush
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">Update Calling Group</div>
                <p class="text-muted  font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id' => 'updateCallingGroup', 'class' => 'ajax-form', 'method' => 'POST']) !!}
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="calling_group_name" class="required">Calling Group Name</label>
                                    <input type="text" class="form-control" id="calling_group_name"
                                        name="calling_group_name" value="{{ $groups->calling_group_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="fallback_number" class="required">Falback Number</label>
                                    <input type="number" class="form-control" id="fallback_number" name="fallback_number"
                                        value="{{ $groups->fallback_number }}">
                                </div>
                                <div class="form-group">
                                    <label for="employees" class="required">Select Employees</label>
                                    <div id="employees">
                                        <select class="select2 select2-multiple" multiple="multiple" id="employees"
                                            name="employees[]" data-placeholder="Choose Employees ...">
                                            @foreach ($employee as $item)
                                                @php
                                                    $employees = json_decode($groups->employees);
                                                    // dd($employees);
                                                @endphp
                                                <option value="{{ $item->user_id }}"
                                                    {{ in_array($item->user_id, $employees) ? 'selected' : '' }}>
                                                    {{ $item->user->name }}
                                                    {{ $item->user_id == Auth::user()->id ? '(YOU)' : '' }} </option>
                                            @endforeach

                                        </select>
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
    <script>
        $(".select2").select2({
            formatNoMatches: function() {
                return "No record found.";
            }
        });
    </script>
    <script>
        $('#save-form').click(function() {
            const url = "{{ route('admin.calling-group.update', ':xid') }}"
            const finalUrl = url.replace(':xid', "{{ $groups->id }}")
            $.easyAjax({
                url: finalUrl,
                container: '#updateCallingGroup',
                type: "POST",
                redirect: true,
                data: $('#updateCallingGroup').serialize()
            })
        });
    </script>
@endpush
