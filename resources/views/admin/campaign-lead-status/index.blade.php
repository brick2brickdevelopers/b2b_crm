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
            <a href="{{ route('admin.campaign-lead-status.create') }}"
            class="btn btn-outline btn-success btn-sm">Add Campaign Lead Status<i class="fa fa-plus"
                                                                                               aria-hidden="true"></i></a>
            <ol class="breadcrumb">
                <li><a href="">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')
    <div class="row">

        <div class="col-xs-12">
            <div class="white-box">
                <div class="card">
                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Campaign Lead Status Name</th>
                                      
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($callOutcomes as $callOutcome)
                                      
                                        <tr>
                                            <td>{{ $callOutcome->id }}</td>
                                            <td>{{ $callOutcome->name }}</td>
                                            <td class=" text-center">
                                                <div class="btn-group dropdown m-r-10">
                                                    <button aria-expanded="false" data-toggle="dropdown"
                                                        class="btn btn-default dropdown-toggle waves-effect waves-light"
                                                        type="button"><i class="fa fa-gears "></i></button>
                                                    <ul role="menu" class="dropdown-menu pull-right">
                                                        <li><a href="{{ route('admin.campaign-lead-status.edit', $callOutcome->id) }}"
                                                                type="button"><i class="fa fa-pencil"
                                                                    aria-hidden="true"></i>
                                                                Edit</a></li>
                                                     
                                                        <li><a
                                                                href="{{ route('admin.campaign-lead-status.destroy', $callOutcome->id) }}"><i
                                                                    class="fa fa-times" aria-hidden="true"></i>
                                                                Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                           
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                          
                        </div>
                    </div>
                </div>



            </div>
            <!-- end col-->
        </div>
    @endsection
   
