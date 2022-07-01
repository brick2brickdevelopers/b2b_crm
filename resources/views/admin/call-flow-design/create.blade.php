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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.add') Call Flow Design</div>
                <p class="text-muted  font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id' => 'createCallingGroup', 'class' => 'ajax-form', 'method' => 'POST']) !!}
                                <div class="form-group">
                                    <label for="greeting" class="required">Welcome greeting</label>
                                    <div id="greeting">
                                        <select class="select2 select2-multiple" multiple="multiple" id="greetings"
                                            name="greetings" data-placeholder="Choose Greetings ...">


                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="menu" class="required">Need a menu</label>
                                    <div class="switchery-demo">
                                        <input id="menu-switch" type="checkbox" class="js-switch" data-size="small"
                                            data-color="#00c292" />
                                    </div>
                                </div>
                                <div class="form-group menu-message">
                                    <label for="menu_message" class="required">Menu message</label>
                                    <div id="menu_message">
                                        <select class="select2 select2-multiple" multiple="multiple" id="menu_message"
                                            name="menu_message" data-placeholder="Choose Menu ...">


                                        </select>
                                    </div>
                                </div>
                                <div class="form-group extension-directory">
                                    <label for="menu_message" class="required">Extension Directory</label>
                                    <div id="extension">
                                        <select class="select2 select2-multiple" multiple="multiple" id="extension"
                                            name="extension" data-placeholder="Choose Department ...">


                                        </select>
                                    </div>
                                </div>
                                <div class="form-group extension-directory-with-number">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="extension_directory_with_number" class="required">Extension
                                                Number</label>
                                            <div id="extension-directory-with-number">
                                                <select class="form-control" id="" name=""
                                                    data-placeholder="Choose Department ...">


                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="extension_directory_with_number" class="required">Extension
                                                Directory</label>
                                            <div id="">
                                                <select class="select2 select2-multiple" multiple="multiple" id=""
                                                    name="" data-placeholder="Choose Department ...">


                                                </select>
                                            </div>
                                        </div>
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
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());

        });
    </script>
    <script>
        $(".select2").select2({
            formatNoMatches: function() {
                return "No record found.";
            }
        });
    </script>
    <script>
        $(".menu-message").hide();
        $(".extension-directory-with-number").hide();
        $("#menu-switch").on("change", function() {
            if ($(this).is(':checked')) {
                $(".menu-message").show();
                $(".extension-directory-with-number").show();
                $(".extension-directory").hide()
            } else {
                $(".menu-message").hide();
                $(".extension-directory-with-number").hide();
                $(".extension-directory").show()
            }
        })
    </script>
    <script>
        $('#save-form').click(function() {
            $.easyAjax({
                url: '{{ route('admin.calling-group.store') }}',
                container: '#createCallingGroup',
                type: "POST",
                redirect: true,
                data: $('#createCallingGroup').serialize()
            })
        });
    </script>
@endpush
