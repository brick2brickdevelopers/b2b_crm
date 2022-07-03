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
                                        <div class=" col-md-6 appendHere"></div>
                                        <div class="col-md-6">
                                            <label for="extension_directory_with_number" class="required">Extension
                                                Directory</label>
                                            <div id="">
                                                <select class="select2 select2-multiple" multiple="multiple" id=""
                                                    name="" data-placeholder="Choose Department ...">


                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">

                                                <tbody id="tbody">

                                                </tbody>
                                            </table>
                                        </div>
                                        <button class="btn btn-md btn-primary" id="addBtn" type="button">
                                            Add new Row
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="menu" class="required">Do you need voicemail with each department
                                            ?</label>

                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-check-input" type="checkbox" id="inline" value="option2">

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="menu" class="required">Do you want call flow for non working
                                    hours?</label>
                                <div class="switchery-demo">
                                    <input id="menu-switch_work_hour" type="checkbox" class="js-switch"
                                        data-size="small" data-color="#00c292" />
                                </div>
                            </div>

                            <div class="menu-switch_work_hour_row">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Set Time</label>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="">
                                                <select class="select2 select2-multiple" multiple="multiple"
                                                    id="" name="" data-placeholder="Start">


                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="">
                                                <select class="select2 select2-multiple" multiple="multiple"
                                                    id="" name="" data-placeholder="End">


                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="greeting" class="required">Welcome greeting</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="greeting">
                                                <select class="select2 select2-multiple" multiple="multiple"
                                                    id="greetings" name="greetings"
                                                    data-placeholder="Choose Greetings ...">


                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="greeting" class="required">Voicemail message</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="greeting">
                                                <select class="select2 select2-multiple" multiple="multiple"
                                                    id="greetings" name="greetings"
                                                    data-placeholder="Choose Greetings ...">


                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="menu" class="required">Do you want call flow for non working days?</label>
                                <div class="switchery-demo">
                                    <input id="menu-switch_work_day" type="checkbox" class="js-switch" data-size="small"
                                        data-color="#00c292" />
                                </div>
                            </div>

                            <div class="menu-switch_work_day_row">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Select Days</label>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                                    value="option2">
                                                <label class="form-check-label" for="inlineCheckbox2">Mon</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                                    value="option3">
                                                <label class="form-check-label" for="inlineCheckbox3">Tue</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                                    value="option2">
                                                <label class="form-check-label" for="inlineCheckbox2">Wed</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                                    value="option3">
                                                <label class="form-check-label" for="inlineCheckbox3">Thu</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                                    value="option2">
                                                <label class="form-check-label" for="inlineCheckbox2">Fri</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                                    value="option3">
                                                <label class="form-check-label" for="inlineCheckbox3">Sat</label>
                                            </span>
                                            <span class="form-check form-check-inline selectdays-otbox">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                                    value="option2">
                                                <label class="form-check-label" for="inlineCheckbox2">Sun</label>
                                            </span>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="greeting" class="required">Welcome greeting</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="greeting">
                                                <select class="select2 select2-multiple" multiple="multiple"
                                                    id="greetings" name="greetings"
                                                    data-placeholder="Choose Greetings ...">


                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="greeting" class="required">Voicemail message</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="greeting">
                                                <select class="select2 select2-multiple" multiple="multiple"
                                                    id="greetings" name="greetings"
                                                    data-placeholder="Choose Greetings ...">


                                                </select>
                                            </div>
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


    <script>
        $(".menu-switch_work_hour_row").hide()
        $("#menu-switch_work_hour").on("change", function() {
            if ($(this).is(':checked')) {
                $(".menu-switch_work_hour_row").show();
            } else {
                $(".menu-switch_work_hour_row").hide();
            }
        })
    </script>

    <script>
        $(".menu-switch_work_day_row").hide()
        $("#menu-switch_work_day").on("change", function() {
            if ($(this).is(':checked')) {
                $(".menu-switch_work_day_row").show();
            } else {
                $(".menu-switch_work_day_row").hide();
            }
        })
    </script>
    {{-- <script>
     $('#addBtn').click(function() {
            // var count = 10;
            // for (var i=0; i<count; i++){
                // $('div.appendHere').append('<div class="appendedDIVs">document.write(i)</div>');

                $('.appendHere').append(`<div class="col-md-6">
                                            <label for="extension_directory_with_number" class="required">Extension
                                                Directory</label>
                                            <div id="">
                                                <select class="select2 select2-multiple" multiple="multiple" id=""
                                                    name="" data-placeholder="Choose Department ...">


                                                </select>
                                            </div>
                                        </div>`);

                
          //  }
     })
</script> --}}

    <script>
        $(document).ready(function() {

            // Denotes total number of rows
            var rowIdx = 0;

            // jQuery button click event to add a row
            $('#addBtn').on('click', function() {

                // Adding a row inside the tbody.
                $('#tbody').append(`<tr id="R${++rowIdx}">
             <td class="row-index text-center">
                <div class="col-md-6">
                                            <label for="extension_directory_with_number" class="required">Extension
                                                Directory</label>
                                            <div id="">
                                                <select class="select2 select2-multiple" multiple="multiple"
                                                    id="greetings" name="greetings"
                                                    data-placeholder="Choose Greetings ...">


                                                </select>
                                            </div>
                                        </div>
             </td>
              <td class="text-center">
                <button class="btn btn-danger remove"
                  type="button"><i class="fa fa-times"></i></button>
                </td>
              </tr>`);
            });

            $('#tbody').on('click', '.remove', function() {


                var child = $(this).closest('tr').nextAll();


                child.each(function() {

                    var id = $(this).attr('id');

                    var idx = $(this).children('.row-index').children('div');

                    var dig = parseInt(id.substring(1));

                    idx.html(`Row ${dig - 1}`);

                    $(this).attr('id', `R${dig - 1}`);
                });

                $(this).closest('tr').remove();

                rowIdx--;
            });
        });
    </script>
@endpush
