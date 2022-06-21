@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}
                <span class="text-info b-l p-l-10 m-l-5">888</span> <span class="font-12 text-muted m-l-5">
                    @lang('modules.dashboard.totalCompanies')</span>
            </h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-6 col-xs-12 text-right bg-title-right">
            {{-- <a href="javascript:;" id="addDefaultLanguage" class="btn btn-outline btn-info btn-sm">@lang('app.manage') @lang('app.defaultLanguage') </a> --}}

            {{-- <a href="{{ route('super-admin.sip-gateway.create') }}" class="btn btn-outline btn-success btn-sm">@lang('app.add') @lang('app.company') <i class="fa fa-plus" aria-hidden="true"></i></a> --}}

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
        <form action="" id="save-form">
            @csrf
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label">Call Purpose</label>
                    <input class="form-control" type="text" name="purpose" id="purpose" data-style="form-control" required="true">
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12">&nbsp;</label>
                    <button type="submit" name="submit" id="save-button" class="btn btn-success col-md-6"><i class="fa fa-check"></i>
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
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable">
                        <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Call Purpose</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        <tbody>
                        @foreach($callPurpose as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->purpose }}</td>
                                <td class=" text-center">
                                            <button onclick="editCallPurpose({{ $item }})" data-toggle="modal" data-target="#editCallPurpose" class="btn btn-default dropdown-toggle waves-effect waves-light"><i class="fa fa-pencil"></i></button>
                                            <a type="button" href="{{ route('admin.campaigns.call-purpose.delete', $item->id) }}" class="btn btn-danger dropdown-toggle waves-effect waves-light"><i class="fa fa-trash"></i></a>
                                        </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                    
                    {{-- {!! $html->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!} --}}

                </div>
            </div>
        </div>
    </div>
    {{-- Ajax Modal --}}
    <div class="modal fade bs-modal-md in" id="editCallPurpose" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading">Update Call Purpose</span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="" id="update-form">
                            @csrf
                            {{-- @method('PUT') --}}
                            <div class="col-xs-12 sarv-modal">
                                <div class="form-group">
                                    <label class="control-label">Call Purpose</label>
                                    <input class="form-control purpose-name" type="text" name="purpose" id="purpose"
                                        data-style="form-control">
                                        <input type="text" name="id" class="id" id="id">

                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button type="submit" id="update-button" name="submit" class="btn btn-success"><i class="fa fa-check"></i>
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
    <!-- .row -->
@endsection



@push('footer-script')
    {{-- {!! $html->scripts() !!} --}}

    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>
const editCallPurpose = (e) => {
            console.log(e);
            $(".purpose-name").val(e.purpose)
            $(".id").val(e.id)

        }
        $('#save-button').click(function(event) {
            event.preventDefault()
            $.easyAjax({
                url: "{{ route('admin.campaigns.call-purpose') }}",
                container: '#save-form',
                type: "POST",
                redirect: true,
                data: $('#save-form').serialize()
            })
        });

        $('#update-button').click(function(event) {
            event.preventDefault()
            $.easyAjax({
                url: "{{ route('admin.campaigns.call-purpose') }}",
                container: '#update-form',
                type: "POST",
                redirect: true,
                data: $('#update-form').serialize()
            })
        });
</script>
@endpush
