@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}

            </h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-6 col-xs-12 text-right bg-title-right">


            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}">
@endpush

@section('filter-section')
    <div class="row" id="ticket-filters">
        <form action="" id="sip-form">
            @csrf
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label">Company Name</label>
                    <select class="form-control selectpicker" name="company_id" id="company_id" data-style="form-control">
                        @foreach ($company as $item)
                            <option value="{{ $item->id }}">{{ $item->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
          
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label">Did Number</label>
                   

                    <select class="select2 select2-multiple" multiple="multiple" id="didnumber"
                    name="didnumber[]" data-placeholder="Choose DID Numbers ...">
                    @foreach ($didNumbers as $didNumber)
                            <option value="{{ $didNumber->number }}">{{ $didNumber->number }}</option>
                        @endforeach

                </select>
                </div>
            </div>
            
         
         
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12">&nbsp;</label>
                    <button type="submit" id="save-form" class="btn btn-success col-md-6"><i class="fa fa-check"></i>
                        Submit</button>

                </div>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="white-box">
                <div class="table-responsive">
                    {!! $html->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}

                </div>
            </div>
        </div>
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

    <script>
        $(".select2").select2({
            formatNoMatches: function() {
                return "No record found.";
            }
        });
    </script>

    {!! $html->scripts() !!}

    <script>
        // function changeStatus(id) {
        //     $.post("{{ route('super-admin.sip-gateway.change') }}", {
        //         id: id,
        //         _token: "{{ csrf_token() }}"
        //     })
        //     $("#dataTableBuilder").DataTable().ajax.reload();
        //     $(".switch-event1").switchButton()
        // }

        $('#save-form').click(function(evt) {
            evt.preventDefault();

            $.easyAjax({
                url: "{{ route('super-admin.did-managment.store') }}",
                container: '#sip-form',
                type: "POST",
                redirect: true,
                data: $('#sip-form').serialize()
            })
        });
        $('.sarv').hide()
        $("#type").on('change', function() {

            if ($(this).val() === "1") {
                $('.sarv').hide()
            } else {
                $('.sarv').show()
                $('.sarvInput').attr('required', true)
            }
        })
        //modal
        $('.sarv-modal').hide()
        $("#type-modal").on('change', function() {
            if ($(this).val() === "1") {
                $('.sarv-modal').hide()
            } else {
                $('.sarv-modal').show()
                $('.sarvInput').attr('required', true)
            }
        })
        const editData = (e) => {
            console.log(e);
            $("div.company-modal select").val(e.company_id).change();
            $("div.type-modal select").val(e.type).change();
            $(".caller-id").val(e.caller_id)
            $(".endpoint").val(e.endpoint)
            $(".user").val(e.key)
            $(".token").val(e.token)
            $(".id").val(e.id)
        }
        $('#update-data').click(function(evt) {
            evt.preventDefault();

            $.easyAjax({
                url: "{{ route('super-admin.did-managment.store') }}",
                container: '#update-form',
                type: "POST",
                redirect: true,
                data: $('#update-form').serialize()
            })
        });
    </script>
@endpush
