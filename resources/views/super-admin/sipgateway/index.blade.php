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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet"
        href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
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
                    <label class="control-label">Server Type</label>
                    <select class="form-control selectpicker" name="type" id="type" data-style="form-control">
                        <option value="1">SIP</option>
                        <option value="2">SARV</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label">Caller ID</label>
                    <input class="form-control" type="text" name="caller_id" id="caller_id" data-style="form-control"
                        required="true">
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label">End Point</label>
                    <input class="form-control" type="url" name="endpoint" id="endpoint" data-style="form-control"
                        required="true">
                </div>
            </div>
            <div class="col-xs-12 sarv">
                <div class="form-group">
                    <label class="control-label">User</label>
                    <input class="form-control sarvInput" type="text" name="user" id="user"
                        data-style="form-control">
                </div>
            </div>
            <div class="col-xs-12 sarv">
                <div class="form-group">
                    <label class="control-label">Token</label>
                    <input class="form-control sarvInput" type="text" name="token" id="token"
                        data-style="form-control">
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12">&nbsp;</label>
                    <button type="submit" id="save-form" class="btn btn-success col-md-6"><i class="fa fa-check"></i>
                        @lang('app.apply')</button>

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
    {{-- Ajax Modal --}}
    <div class="modal fade bs-modal-md in" id="editSIP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading">Change Package</span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="" id="update-form">
                            @csrf
                            {{-- @method('PUT') --}}
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Company Name</label>
                                    <select class="form-control company-modal selectpicker" name="company_id"
                                        id="company_id" data-style="form-control">
                                        {{-- <option value="all">@lang('app.all')</option> --}}
                                        @foreach ($company as $item)
                                            <option value="{{ $item->id }}">{{ $item->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Server Type</label>
                                    <select class="form-control type-modal selectpicker" name="type" id="type-modal"
                                        data-style="form-control">
                                        {{-- <option value="all">@lang('app.all')</option> --}}
                                        <option value="1">SIP</option>
                                        <option value="2">SARV</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Caller ID</label>
                                    <input class="form-control caller-id" type="text" name="caller_id" id="caller_id"
                                        data-style="form-control">
                                    <input type="hidden" class="id" name="id">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">End Point</label>
                                    <input class="form-control endpoint" type="url" name="endpoint" id="endpoint"
                                        data-style="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 sarv-modal">
                                <div class="form-group">
                                    <label class="control-label">User</label>
                                    <input class="form-control sarvInput user" type="text" name="user"
                                        id="user" data-style="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 sarv-modal">
                                <div class="form-group">
                                    <label class="control-label">Token</label>
                                    <input class="form-control sarvInput token" type="text" name="token"
                                        id="token" data-style="form-control">

                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button type="submit" id="update-data" class="btn btn-success"><i class="fa fa-check"></i>
                            @lang('app.update')</button>

                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('app.back')</button>
                    </div>
                </div>
            </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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

    {!! $html->scripts() !!}

    <script>
        function changeStatus(id) {
            $.post("{{ route('super-admin.sip-gateway.change') }}", {
                id: id,
                _token: "{{ csrf_token() }}"
            })
            $("#dataTableBuilder").DataTable().ajax.reload();
            $(".switch-event1").switchButton()
        }

        $('#save-form').click(function(evt) {
            evt.preventDefault();

            $.easyAjax({
                url: "{{ route('super-admin.sip-gateway.store') }}",
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
                url: "{{ route('super-admin.sip-gateway.store') }}",
                container: '#update-form',
                type: "POST",
                redirect: true,
                data: $('#update-form').serialize()
            })
        });
    </script>
@endpush
