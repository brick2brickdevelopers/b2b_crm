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
            <a href="{{ route('admin.calling-group.create') }}" class="btn btn-outline btn-success btn-sm">Add Calling Group
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
                                <th>Calling Group Name</th>
                                <th>Fallback Number</th>
                                <th>@lang('app.employees')</th>
                                <th>Is Default</th>
                                <th>@lang('app.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $group)
                                @php
                                    $employee = json_decode($group->employees);
                                    // dd(App\EmployeeDetails::whereIn('user_id',$employee)->get());
                                @endphp
                                <tr id="group{{ $group->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $group->calling_group_name }} <label
                                            class="label label-success">{{ count(json_decode($group->employees)) }}
                                            @lang('modules.projects.members')</label></td>
                                    <td>{{ $group->fallback_number }}</td>
                                    <td>
                                        @forelse(App\EmployeeDetails::whereIn('user_id',$employee)->get() as $item)
                                            <img data-toggle="tooltip"
                                                data-original-title="{{ ucwords($item->user->name) }}"
                                                src="{{ $item->user->image_url }}" alt="user" class="img-circle"
                                                width="25" height="25">
                                        @empty
                                            @lang('messages.noRecordFound')
                                        @endforelse
                                    </td>
                                    <td>
                                        <div class="switchery-demo">
                                            <input onchange="defaultSwitch({{ $group->id }})" type="checkbox"
                                                {{ $group->is_default ? 'checked' : '' }} class="js-switch"
                                                data-size="small" data-color="#00c292" />
                                        </div>
                                    </td>
                                    <td>

                                        <div class="btn-group dropdown m-r-10">
                                            <button aria-expanded="false" data-toggle="dropdown"
                                                class="btn btn-default dropdown-toggle waves-effect waves-light"
                                                type="button"><i class="fa fa-gears "></i></button>
                                            <ul role="menu" class="dropdown-menu pull-right">
                                                <li><a href="{{ route('admin.calling-group.edit', [$group->id]) }}"><i
                                                            class="icon-settings"></i> Edit</a></li>
                                                <li><a href="{{ route('admin.calling-group.destroy', $group->id) }}"
                                                        data-group-id="{{ $group->id }}" class="sa-params"><i
                                                            class="fa fa-times" aria-hidden="true"></i>
                                                        @lang('app.delete') </a></li>

                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="icon-layers"></i>
                                                </div>
                                                <div class="title m-b-15">
                                                    <p>Seems like no Calling Group. Create your first Calling Group</p>
                                                </div>
                                                <div class="subtitle">
                                                    <a href="{{ route('admin.calling-group.create') }}"
                                                        class="btn btn-outline btn-success btn-sm">Add Calling Group
                                                        <i class="fa fa-plus" aria-hidden="true"></i></a>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
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
