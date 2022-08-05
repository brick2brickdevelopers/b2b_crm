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
                <div class="panel-heading">Edit Call Outcome</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="POST" action="" class="form-horizontal" id="createCallOutcome">
                            @csrf
                            <div class="form-group row mb-2">
                                <label class="col-lg-2 col-form-label" for="name">Call Outcome Name</label>
                                <div class="col-lg-10">
                                    <input type="text" id="name" name="name" value="{{ $callOutcome->name }}"
                                        class="form-control" required>
                                </div>
                            </div>
                           
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

    <script>
        $('#save-form').click(function(event) {
            event.preventDefault()
            const url = "{{ route('admin.call-outcome.update', ':xid') }}"
            const finalUrl = url.replace(':xid', "{{ $callOutcome->id }}")
            $.easyAjax({
                url: finalUrl,
                container: '#createCallOutcome',
                type: "POST",
                redirect: true,
                data: $('#createCallOutcome').serialize()
            })
            console.log($('#createCallOutcome').serialize())
        });
    </script>
@endpush
