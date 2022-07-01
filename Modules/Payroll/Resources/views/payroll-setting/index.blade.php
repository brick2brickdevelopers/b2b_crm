@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ $pageTitle }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.settings.index') }}">@lang('app.menu.settings')</a></li>
                <li class="active">{{ $pageTitle }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')


@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.menu.payroll')  @lang('app.menu.settings')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('payroll::sections.payroll_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="white-box">
                                        <h3>@lang('payroll::modules.payroll.FiledToShow')</h3>

                                        {!! Form::open(['id'=>'createTypes','class'=>'ajax-form','method'=>'POST']) !!}
                                        <div class="form-body">
                                            @if(isset($fields) && sizeof($fields) > 0)
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <div class="checkbox checkbox-info  col-md-10">
                                                                    <input id="select_all_field" name="select_all_field" @if($fields->count() == sizeof($extraFields)) checked @endif class="select_all_permission" type="checkbox">
                                                                    <label for="select_all_field">@lang('modules.permission.selectAll')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="row form-group module-in-package">
                                                                @foreach($fields as $field)
                                                                    <div class="col-md-2">
                                                                        <div class="checkbox checkbox-inline checkbox-info m-b-10">
                                                                            <input id="{{$field->name.'_'.$field->id}}" name="{{ $field->name }}" value="{{ $field->id }}" class="module_checkbox"
                                                                                   @if(in_array($field->id, $extraFields)) checked @endif
                                                                                   type="checkbox">
                                                                            <label for="{{$field->name.'_'.$field->id}}">{{$field->label}}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <p>@lang('payroll::modules.payroll.noRecord')</p>
                                                    </div>
                                                </div>
                                                <hr>
                                            @endif
                                        </div>
                                        @if(sizeof($fields) > 0)
                                            <div class="form-actions">
                                                <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
                                            </div>
                                        @endif
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')

<script type="text/javascript">

    $('.select_all_permission').change(function () {
        if($(this).is(':checked')){
            $('.module_checkbox').prop('checked', true);
        } else {
            $('.module_checkbox').prop('checked', false);
        }
    });
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.payroll-setting.store')}}',
            container: '#createTypes',
            type: "POST",
            redirect: true,
            data: $('#createTypes').serialize(),
            success: function (data) {
                // if (data.status == 'success') {
                //     window.location.reload();
                // }
            }
        })
    });


</script>


@endpush

