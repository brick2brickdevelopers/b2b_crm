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
            <a href="{{ route('admin.call-flow-design.create') }}" class="btn btn-outline btn-success btn-sm">Add Call Flow
                Design
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
                                <th>Company Id</th>
                                <th>Menu</th>
                                
                                <th>@lang('app.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($call_flow_diagrams as $key => $call_flow_diagram )
                            <tr>
                                <th>{{ $key + 1 }}</th>
                                <th>{{ $call_flow_diagram->name }}</th>
                                <th>{{ $call_flow_diagram->company_id }}</th>
                                <th>
                                {{$call_flow_diagram->menu=='1' ? "Enable" : "Disabled"}}
                                </th>
                                <td class=" text-center">
                                    <div class="btn-group dropdown m-r-10">
                                        <button aria-expanded="false" data-toggle="dropdown"
                                            class="btn btn-default dropdown-toggle waves-effect waves-light"
                                            type="button"><i class="fa fa-gears "></i></button>
                                        <ul role="menu" class="dropdown-menu pull-right">
                                            <li><a href="{{ route('admin.call-flow-design.edit', $call_flow_diagram->id) }}"
                                                    type="button"><i class="fa fa-pencil"
                                                        aria-hidden="true"></i>
                                                    Edit</a></li>
                                            {{-- <li><a href="{{ route('admin.call-flow-design.show', $call_flow_diagram->id) }}"><i
                                                        class="fa fa-search" aria-hidden="true"></i>
                                                    View</a></li> --}}
                                            <li><a
                                                    href="{{ route('admin.call-flow-design.destroy', $call_flow_diagram->id) }}"><i
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

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());

        });
    </script>
    <script>
        function defaultSwitch(id) {
            // alert('ok');
            var url = '{{ route('admin.calling-group.default-switch') }}'
            console.log(id);
            $.easyAjax({
                url: url,
                type: "POST",
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                }
            })

        }
    </script>
@endpush
