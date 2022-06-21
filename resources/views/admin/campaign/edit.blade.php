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
                <li><a href="{{ route('admin.employees.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
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
                <div class="panel-heading">Create Campaign</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                            <form method="POST" action="" class="form-horizontal" id="createCampaign">
                                @csrf
                                <div class="form-group row mb-2">
                                    <label class="col-lg-2 col-form-label" for="name">Campaign Name</label>
                                    <div class="col-lg-10">
                                        <input type="text" id="name" name="name" value="{{ $campaign->name }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-lg-2 col-form-label" for="type">Campaign Type</label>
                                    <div class="col-lg-10">
                                        <input type="text" id="type" name="type" value="{{ $campaign->type }}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-lg-2 col-form-label" for="status">Campaign Status</label>
                                    <div class="col-lg-10">
                                        <select name="status" class="form-control">
                                            <option value="1" {{ $campaign->status==1? 'selected':'' }}>Pause</option>
                                            <option value="2" {{ $campaign->status==2? 'selected':'' }}>Stop</option>
                                            <option value="3" {{ $campaign->status==3? 'selected':'' }}>Resume</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-lg-2 col-form-label" for="status">Start Date</label>
                                    <div class="col-lg-10">
                                        {{-- <input type="text" class="form-control" id="start_date" name="start_date"> --}}
                                        <input type="text" autocomplete="off"  name="start_date" id="start_date" value="{{ $campaign->start_date }}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-lg-2 col-form-label" for="status">End Date</label>
                                    <div class="col-lg-10">
                                        <input type="text" autocomplete="off" class="form-control" id="end_date" value="{{ $campaign->end_date }}" name="end_date">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-lg-2 col-form-label" for="status">Call To Call Gap</label>
                                    <div class="col-lg-10">
                                        <input type="number" class="form-control" id="call_to_call_gap" name="call_to_call_gap" value="{{ $campaign->call_to_call_gap }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-lg-2 col-form-label" for="status">Break Time</label>
                                    <div class="col-lg-10">
                                        <input type="number" class="form-control" id="break_time" name="break_time" value="{{ $campaign->call_to_call_gap }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-lg-2 col-form-label" for="caller_id">Caller ID</label>
                                    <div class="col-lg-10">
                                        <input type="number" class="form-control" id="caller_id" name="caller_id" value="{{ $campaign->call_to_call_gap }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-lg-2" for="">
                                        <select class="form-control" id="chooseAgentLabel">
                                            <option value="1">Agent</option>
                                            <option value="2">Agent Group</option>
                                        </select>
                                    </label>




                                    <div class="col-lg-10" id="agentselect">
                                        <select class="select2" multiple="multiple" id="agent" name="agent[]" data-placeholder="Choose Agent ...">
                                            @foreach ($employee as $item)
                                                <option value="{{ $item->id }}" {{ $item->id?'selected':'' }}>{{ $item->user->name }} {{ $item->user_id == Auth::user()->id ? '(YOU)' : '' }} </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-lg-10" id="agentgroupselect">
                                        <select class="select2 select2-multiple" multiple="multiple" data-placeholder="Choose Agent Group ...">

                                            @foreach ($teams as $item)
                                                <option value="{{ $item->id }}" >{{ $item->team_name }}</option>
                                            @endforeach

                                        </select>
                                    </div>



                                    

                                    <div class="form-group row mt-5">
                                        <div class="col-lg-12">
                                            @method('PATCH')
                                            <button id="save-form" class="btn btn-success float-right">Save</button>
                                        </div>
                                    </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end col-->
        </div>
        <!-- end row-->

    </div>

    </div> <!-- container -->
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
        formatNoMatches: function () {
            return "No record found.";
        }
    });
</script>
<script>
        $(document).ready(function() {
            $('#agentgroupselect').hide();
            // $('select option:contains("1")').prop('selected',true);
            $('#chooseAgentLabel').on('change', function() {
                if ($(this).val() == 1) {
                    $('#agentselect').show();
                    $('#agentgroupselect').hide();
                } else {
                    $('#agentselect').hide();
                    $('#agentgroupselect').show();
                }

            });
        });
    </script>
<script>

    $("#start_date, #end_date, .date-picker").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

</script>
<script>
        $('#save-form').click(function(event) {
            event.preventDefault()
            const url="{{ route('admin.campaigns.update', ':xid') }}"
            const finalUrl=url.replace(':xid',"{{ $campaign->id }}")
            $.easyAjax({
                url: finalUrl,
                container: '#createCampaign',
                type: "POST",
                redirect: true,
                data: $('#createCampaign').serialize()
            })
            console.log($('#createCampaign').serialize())
        });
    </script>
@endpush

