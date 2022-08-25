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
                <div class="panel-heading">Edit IVR Voicemail</div>
                <p class="text-muted  font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id' => 'updateIvrVoicemail', 'class' => 'ajax-form', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                                @method('PATCH')
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="greeting" class="required">Voicemail Name</label>
                                            <input type="text" name="name" id="name"
                                                value="{{ $voicemails->name }}" class="form-control" autocomplete="nope">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Audio Clip File" class="required">Audio Clip File</label>
                                            <input type="file" name="audio_clip" id="name" class="form-control"
                                                autocomplete="nope">
                                        </div>
                                    </div>

                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label for="" class="required">Type</label>
                                            <select class="form-control " name="type" id="voice" data-style="form-control">
                              
                                                <option value="general" @if ("general" == $voicemails->type) selected @endif>General</option>
                                                <option value="department" @if ("department" == $voicemails->type) selected @endif>Department</option>
                                           
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="departmentA">
                                        <label class="control-label" >Department<span style="color:red">*</span></label>
                                        <div class="form-group">
                                            <select name="department_id" id="" class="form-control">
                                                @foreach($departments as $department)
                                                     <option value="{{ $department->id }}" @if ($department->id == $voicemails->department_id) selected @endif>{{ $department->team_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <button type="submit" id="save-form"
                                    class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.save')
                                </button>
                                <a href="{{ route('admin.ivr-greetings.index') }}"
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
        $('#updateIvrVoicemail').submit(function(e) {
            e.preventDefault();
            const url = "{{ route('admin.ivr-voicemail.update', ':xid') }}"
            const finalUrl = url.replace(':xid', "{{ $voicemails->id }}")
            $.easyAjax({
                url: finalUrl,
                container: '#updateIvrVoicemail',
                type: "POST",
                file: true,
                redirect: true,
                dataType: 'JSON',
                data: new FormData(this),
                contentType: false,
                processData: false,
            })
        });
    </script>

<script>
    $('#departmentA').hide();
    
    $('#voice').on('change', function() {

        if ($(this).val() === 'department') {
            $('#departmentA').show();

        } else {
            $('#departmentA').hide();

        }
    })
</script>
@endpush
