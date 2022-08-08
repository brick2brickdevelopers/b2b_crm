@php use App\ManualLoggedCall; @endphp
@extends('layouts.app')
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right bg-title-right">
            <!-- {{-- @if (!$campaigns->isEmpty()) --}}
            <a href="" class="btn btn-outline btn-success btn-sm">@lang('app.add') Campaign <i class="fa fa-plus"
                    aria-hidden="true"></i></a>
            {{-- @endif --}} -->
            <ol class="breadcrumb">
                <li><a href="">@lang('app.menu.home')</a></li>
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
    <link rel="stylesheet"
        href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
@endpush
@section('content')
    <div class="row">

        <div class="col-xs-12">
            <div class="white-box">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Outbound Campaign</h4>
                        <p>P2P calls and pre-recorded voice messages or IVR based campaigns for yourcustomers</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Campaign Name</th>
                                        <th>Date</th>
                                        <th>Leads Count</th>
                                        <th>Leads Status</th>
                                        <th>Dialing Status</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($campaigns as $campaign)
                                        @php
                                            if ($campaign->start_date != null && $campaign->start_date != '') {
                                                $startDate = date('M d, Y', strtotime($campaign->start_date));
                                            } else {
                                                $startDate = '';
                                            }
                                            
                                            if ($campaign->end_date != null && $campaign->end_date != '') {
                                                $endDate = date('M d, Y', strtotime($campaign->end_date));
                                            } else {
                                                $endDate = '';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $campaign->id }}</td>
                                            <td>{{ $campaign->name }}</td>
                                            <td>{{ $campaign->start_date }} to {{ $campaign->end_date }}</td>
                                            <td>Total Leads: {{ $campaign->leads->count() }}</td>

                                            @php
                                                $available = ManualLoggedCall::where('campaign_id',$campaign->id)->where('call_status','=',0)->count();
                                                $completed = ManualLoggedCall::where('campaign_id',$campaign->id)->where('call_status','=',1)->count();
                                                $follow = ManualLoggedCall::where('campaign_id',$campaign->id)->where('call_status','=',2)->count();
                                            @endphp
                                            <td>
                                                <ul class="p-l-20">
                                                    <li>Available : {{ $available }}</li>
                                                    <li>Completed : {{ $completed }}</li>
                                                    <li>Follow Up : {{ $follow }}</li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="p-l-20">
                                                    @foreach ($callOutcomes as $callOutcome)
                                                    @php $calOutcomeCount = ManualLoggedCall::where('campaign_id',$campaign->id)->where('call_outcome_id','=',$callOutcome->id)->count();@endphp
                                                    <li>{{ $callOutcome->name }} : {{ $calOutcomeCount }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                @if ($campaign->status == 1)
                                                    <span class="badge bg-success badge-pill">Pause</span>
                                                @elseif($campaign->status == 2)
                                                    <span class="badge bg-warning badge-pill">Stop</span>
                                                @elseif($campaign->status == 3)
                                                    <span class="badge bg-danger badge-pill">Resume</span>
                                                @else
                                                    <span class="badge bg-warning badge-pill">N.A</span>
                                                @endif
                                            </td>
                                            <td class=" text-center">
                                                <div class="btn-group dropdown m-r-10">
                                                    <button aria-expanded="false" data-toggle="dropdown"
                                                        class="btn btn-default dropdown-toggle waves-effect waves-light"
                                                        type="button"><i class="fa fa-gears "></i></button>
                                                    <ul role="menu" class="dropdown-menu pull-right">
                                                        <li><a href="{{ route('admin.campaigns.edit', $campaign->id) }}"
                                                                type="button"><i class="fa fa-pencil"
                                                                    aria-hidden="true"></i>
                                                                Edit</a></li>
                                                        <li><a href="{{ route('admin.campaigns.view', $campaign->id) }}"><i
                                                                    class="fa fa-search" aria-hidden="true"></i>
                                                                View</a></li>
                                                        <li><a
                                                                href="{{ route('admin.campaigns.destroy', $campaign->id) }}"><i
                                                                    class="fa fa-times" aria-hidden="true"></i>
                                                                Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <ul class="pagination">
                                <!-- Previous Page Link -->
                                <li class="page-item disabled"><span class="page-link">«</span></li>

                                <!-- Pagination Elements -->
                                <!-- "Three Dots" Separator -->

                                <!-- Array Of Links -->
                                <li class="page-item active"><span class="page-link">1</span></li>

                                <!-- Next Page Link -->
                                <li class="page-item disabled"><span class="page-link">»</span></li>
                            </ul>

                        </div>
                    </div>
                </div>



            </div>
            <!-- end col-->
        </div>
    @endsection
    @push('footer-script')
        <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
        <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
        <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @endpush
