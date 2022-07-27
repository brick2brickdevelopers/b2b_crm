@extends('layouts.app')

@section('page-title')
    <style>
        .card-body {
            padding: 15px
        }
    </style>
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} Dashboard</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->

        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
@endpush

@section('content')
    <div class="row">



        <div class="col-md-12">
            <div class="white-box">
                <ul class="nav customtab nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#available-tab" aria-controls="available-tab" role="tab" data-toggle="tab"
                            aria-expanded="true"><i class="ti-ticket"></i> Available</a>
                    </li>
                    <li role="presentation" class=""><a href="#completed-tab" aria-controls="completed-tab"
                            role="tab" data-toggle="tab" aria-expanded="false"><i class="icon-graph"></i> Completed</a>
                    </li>
                    <li role="presentation" class=""><a href="#follow-tab" aria-controls="follow-tab" role="tab"
                            data-toggle="tab" aria-expanded="false"><i class="icon-graph"></i> Followup</a></li>
                </ul>
            </div>
        </div>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="available-tab">
                ss
            </div>

            <div role="tabpanel" class="tab-pane" id="completed-tab">
                1afasdfasdfasd
            </div>
            <div role="tabpanel" class="tab-pane" id="follow-tab">
                3sadfasdfadf
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
@endpush
