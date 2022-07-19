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
            <a href="{{ route('admin.ivr-greetings.create') }}" class="btn btn-outline btn-success btn-sm">Add IVR
                Greetings
                <i class="fa fa-plus" aria-hidden="true"></i></a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="white-box">


                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                        id="users-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Audio File</th>
                                <th>@lang('app.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($greetings as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <audio src="/user-uploads/{{ $item->audio_clip }}" controls>
                                        </audio>
                                    </td>
                                    <td class=" text-center">
                                        <div class="btn-group dropdown m-r-10">
                                            <button aria-expanded="false" data-toggle="dropdown"
                                                class="btn btn-default dropdown-toggle waves-effect waves-light"
                                                type="button"><i class="fa fa-gears "></i></button>
                                            <ul role="menu" class="dropdown-menu pull-right">
                                                <li><a href="{{ route('admin.ivr-greetings.edit', $item->id) }}"
                                                        type="button"><i class="fa fa-pencil" aria-hidden="true"></i>
                                                        Edit</a></li>
                                                <li><a href="{{ route('admin.ivr-greetings.destroy', $item->id) }}"><i
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
    <!-- .row -->
@endsection
