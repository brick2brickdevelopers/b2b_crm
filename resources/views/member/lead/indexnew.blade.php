@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
       
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right bg-title-right">
            @if ($user->cans('add_lead'))
                <a href="{{ route('member.leads.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.lead.addNewLead')
                    <i class="fa fa-plus" aria-hidden="true"></i></a>

                    <a href="{{ route('member.leads.dashboard') }}" class="btn btn-outline btn-primary btn-sm">Dashboard
                        <i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush

@if ($user->cans('view_lead'))
    @section('filter-section')
        <div class="row" id="ticket-filters">

            <form action="" id="filter-form">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label">@lang('modules.lead.client')</label>
                        <select class="form-control selectpicker" name="client" id="client" data-style="form-control">
                            <option value="all">@lang('modules.lead.all')</option>
                            <option value="lead">@lang('modules.lead.lead')</option>
                            <option value="client">@lang('modules.lead.client')</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="">@lang('modules.tickets.chooseAgents')</label>
                        <select class="selectpicker form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" id="agent_id"
                            name="agent_id">
                            <option value="all">@lang('modules.lead.all')</option>
                            @foreach ($leadAgents as $emp)
                                <option value="{{ $emp->id }}">{{ ucwords($emp->user->name) }} @if ($emp->user->id == $user->id)
                                        (YOU)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label">@lang('modules.lead.followUp')</label>
                        <select class="form-control selectpicker" name="followUp" id="followUp" data-style="form-control">
                            <option value="all">@lang('modules.lead.all')</option>
                            <option value="pending">@lang('modules.lead.pending')</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12">&nbsp;</label>
                        <button type="button" id="apply-filters" class="btn btn-success col-md-6"><i
                                class="fa fa-check"></i> @lang('app.apply')</button>
                        <button type="button" id="reset-filters" class="btn btn-inverse col-md-5 col-md-offset-1"><i
                                class="fa fa-refresh"></i> @lang('app.reset')</button>
                    </div>
                </div>
            </form>
        </div>
    @endsection
@endif

@section('content')
    <div class="row dashboard-stats">
        <div class="col-md-12 m-b-30">
            <div class="white-box">
                <div class="col-md-4 text-center">
                    <h4><span class="text-dark"></span> <span class="font-12 text-muted m-l-5">
                            @lang('modules.dashboard.totalLeads')</span></h4>
                </div>
                <div class="col-md-4 text-center b-l">
                    <h4><span class="text-info"></span> <span class="font-12 text-muted m-l-5">
                            @lang('modules.dashboard.totalConvertedClient')</span></h4>
                </div>
                <div class="col-md-4 text-center b-l">
                    <h4><span class="text-warning"></span> <span
                            class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalPendingFollowUps')</span></h4>
                </div>
            </div>
        </div>

    </div>

    <div class="row white-box">

        {{-- <div class="col-md-4" style="float:right">
            <div class="form-group" style="margin-top: 7px;">
                <label>Select Columns to show </label>
                <select name="column_name" id="column_name" class="select2 select2-multiple" multiple>
                    @foreach ($column as $key => $item)
                        <option value="{{ $key + 6 }}">{{ $item->label }}</option>
                    @endforeach

                </select>
            </div>
        </div> --}}

        <div class="col-xs-12">

            <div class="white-box">


                <div class="">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                        id="users-table">
                        <thead>
                            <tr>
                                <th>@lang('app.id')</th>
                                <th>@lang('app.clientName')</th>
                                {{-- <th>@lang('modules.lead.companyName')</th> --}}
                                <th>Phone</th>
                                <th>App Client Action</th>
                                <th>@lang('app.createdOn')</th>
                                <th>@lang('modules.lead.nextFollowUp')</th>
                                <th>@lang('app.status')</th>
                                {{-- @foreach ($column as $item)
                                    <th>{{ $item->label }}</th>
                                @endforeach --}}
                                <th>@lang('app.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campaignLeads as $key=>$campaignLead)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$campaignLead->agent->name}}</td>
                                <td>{{$campaignLead->agent->name}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->
    {{-- Ajax Modal --}}
    <div class="modal fade bs-modal-md in" id="followUpModal" role="dialog" aria-labelledby="myModalLabel"
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
        <!-- /.modal-dialog -->
    </div>
    {{-- Ajax Modal Ends --}}
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
   
   
@endpush
