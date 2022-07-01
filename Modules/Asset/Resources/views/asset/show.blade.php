<div class="rpanel-title"> @lang('asset::app.assetInfo') #{{ $asset->id }}<span><i class="ti-close right-side-toggle"></i></span> </div>
<div class="r-panel-body">

    <div class="row">
        <div class="col-xs-12">
            @if ($asset->status == 'available')
                <a href="javascript:;" onclick="lend('{{$asset->id}}');return false;" class="btn btn-primary btn-sm m-b-10 btn-rounded btn-outline pull-right"><i class="fa fa-mail-reply" aria-hidden="true"></i> {{ trans('asset::app.lend') }}</a>
            @endif

            @if ($asset->status == 'lent')
                <a href="javascript:;" onclick="returnAsset('{{ $asset->history->count() > 0 ? $asset->history[0]->id : '' }}' ,'{{ $asset->id }}');return false;" class="btn btn-info btn-sm m-b-10 btn-rounded btn-outline pull-right"><i class="fa fa-mail-reply" aria-hidden="true"></i> {{ trans('asset::app.return') }}</a>
            @endif
            <a href="{{ route('admin.assets.edit', [$asset->id]) }}" class="btn btn-success btn-sm m-b-10 btn-rounded btn-outline pull-right m-r-10"><i class="fa fa-pencil" aria-hidden="true"></i> {{ trans('app.edit') }}</a>
        </div>
        @php
            $class = ['non-functional' => 'danger', 'lent' => 'warning', 'available' => 'success'];
        @endphp
        <div class="col-xs-12" id="task-detail-section">
            <div class="row">

                <div class="col-xs-6 col-md-3 font-12 m-t-10">
                    <label class="font-12" for="">@lang('asset::app.assetName')</label><br>
                    <span class="text-success" >{{ ucfirst($asset->name) }}</span><br>
                </div>

                <div class="col-xs-6 col-md-3 font-12 m-t-10">
                    <label class="font-12" for="">@lang('asset::app.assetType')</label><br>
                    <label class="label label-default text-dark m-l-5 font-light">{{ ucwords($asset->asset_type->name) }}</label><br>
                </div>

                <div class="col-xs-6 col-md-3 font-12 m-t-10">
                    <label class="font-12" for="">@lang('asset::app.status')</label><br>
                    <label class="label label-{{ $class[$asset->status] }} text-dark m-l-5 font-light">{{ucfirst($asset->status)}}</label>
                </div>

                <div class="col-xs-6 col-md-3 font-12 m-t-10">
                    <label class="font-12" for="">@lang('asset::app.serialNumber')</label><br>
                    <span class="text-success" >{{ $asset->serial_number }}</span><br>
                </div>
                <div class="col-xs-6 col-md-3 font-12 m-t-10">
                    <label class="font-12" for="">@lang('asset::app.assetPicture')</label><br>
                    <a href="{{$asset->image_url}}" target="_blank"><img src="{{$asset->image_url}}" alt="" height="100px"></a>
                </div>
                @if($asset->description)
                    <div class="col-xs-12 task-description b-all p-10 m-t-20">
                        {{ ucfirst($asset->description) }}
                    </div>
                @endif

                <div class="col-xs-12 m-t-15">
                    <h5 class="font-bold">@lang('asset::app.history')</h5>
                </div>

                <div class="col-xs-12" id="comment-container">
                    <div id="comment-list">
                        @forelse($asset->history as $history)
                            <div class="row m-b-5 font-12" id="asset-history-{{$history->id}}">
                                <div class="col-xs-12 m-b-10">
                                    <div class="col-xs-3">
                                        <span class="text-dark font-12">@lang('asset::app.lentTo')</span>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <img src="{{ $history->user->image_url }}" alt="user" class="img-circle" width="30" height="30">
                                                <a href="{{ route('admin.employees.show', $history->user->id) }}">{{ ucwords($history->user->name) }}</a>
                                                <br>
                                                <span class="text-muted font-12">{{ ($history->user->designation_name) ? ucwords($history->user->designation_name) : ' ' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12  m-b-10">
                                    <div class="col-xs-3">
                                        <span class="text-dark font-12">@lang('asset::app.dateGiven')</span>
                                    </div>
                                    <div class="col-xs-9">
                                        <span class="text-muted font-12">{{ ucwords($history->date_given->setTimezone($global->timezone)->format('d F Y H:i A')) }} ({{ $history->date_given->setTimezone($global->timezone)->diffForHumans(\Carbon\Carbon::now()->setTimezone($global->timezone)) }})</span>
                                    </div>
                                </div>
                                <div class="col-xs-12  m-b-10">
                                    <div class="col-xs-3">
                                        <span class="text-dark font-12">@lang('asset::app.returnDate')</span>
                                    </div>
                                    <div class="col-xs-9">
                                        <span class="text-muted font-12">{{ !is_null($history->return_date) ? ucwords($history->return_date->setTimezone($global->timezone)->format('d F Y H:i A')). ' ('.$history->return_date->setTimezone($global->timezone)->diffForHumans(\Carbon\Carbon::now()->setTimezone($global->timezone)) .')' : '-' }} </span>
                                    </div>
                                </div>

                                <div class="col-xs-12  m-b-10">
                                    <div class="col-xs-3">
                                        <span class="text-dark font-12">@lang('asset::app.dateOfReturn')</span>
                                    </div>
                                    <div class="col-xs-9">
                                        <span class="text-muted font-12">{{ !is_null($history->date_of_return) ? ucwords($history->date_of_return->setTimezone($global->timezone)->format('d F Y H:i A')). ' ('.$history->date_of_return->setTimezone($global->timezone)->diffForHumans(\Carbon\Carbon::now()->setTimezone($global->timezone)) .')' : '-' }} </span>
                                    </div>
                                </div>

                                <div class="col-xs-12 m-b-10">
                                    <div class="col-xs-3">
                                        <span class="text-dark font-12">@lang('asset::app.returnedBy')</span>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                @if($history->returner)
                                                    <img src="{{ $history->returner->image_url }}" alt="returner" class="img-circle" width="30" height="30">
                                                    <a href="{{ route('admin.employees.show', $history->returner->id) }}">{{ ucwords($history->returner->name) }}</a>
                                                    <br>
                                                    <span class="text-muted font-12">{{ ($history->returner->designation_name) ? ucwords($history->returner->designation_name) : ' ' }}</span>
                                                @else
                                                    <span class="text-muted font-12">-</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12  m-b-10">
                                    <div class="col-xs-3">
                                        <span class="text-dark font-12">@lang('asset::app.notes')</span>
                                    </div>
                                    <div class="col-xs-9">
                                        <span class="text-muted font-12">{{ is_null($history->notes) ? '-' : $history->notes }}</span>
                                    </div>
                                </div>

                                <br>
                                <br>
                                <div class="col-xs-2 text-right">
                                    <a href="javascript:;" data-history-id="{{ $history->id }}" class="text-success m-r-15" onclick="editHistory('{{ $history->id }}');return false;">@lang('app.edit')</a>
                                    <a href="javascript:;" data-history-id="{{ $history->id }}" class="text-danger delete-history">@lang('app.delete')</a>
                                </div>
                            </div>
                            <hr>
                        @empty
                            <div class="col-xs-12">
                                <div class="col-xs-12">
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i
                                                            class="icon-layers"></i>
                                                </div>
                                                <div class="title m-b-15">
                                                    @lang('asset::app.noLendingHistoryFound')
                                                </div>
                                                <div class="subtitle">
                                                    @if ($asset->status == 'available')
                                                        <a href="javascript:;"
                                                           onclick="lend('{{$asset->id}}');return false;"
                                                           class="btn btn-primary btn-sm m-b-10 btn-rounded btn-outline "><i
                                                                    class="fa fa-mail-reply"
                                                                    aria-hidden="true"></i> {{ trans('asset::app.lend') }}
                                                        </a>
                                                    @endif

                                                    @if ($asset->status == 'lent')
                                                        <a href="javascript:;"
                                                           onclick="returnAsset('{{ $asset->history->count() > 0 ? $asset->history[0]->id : '' }}' ,'{{ $asset->id }}');return false;"
                                                           class="btn btn-info btn-sm m-b-10 btn-rounded btn-outline "><i
                                                                    class="fa fa-mail-reply"
                                                                    aria-hidden="true"></i> {{ trans('asset::app.return') }}
                                                        </a>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="assetModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}
    <script>
        $('body').on('click', '.edit-sub-task', function () {
            var id = $(this).data('sub-task-id');
            var url = '{{ route('admin.sub-task.edit', ':id')}}';
            url = url.replace(':id', id);

            $('#subTaskModelHeading').html('Sub Task');
            $.ajaxModal('#subTaskModal', url);
        })

        $('body').on('click', '.delete-history', function () {
            var id = $(this).data('history-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted history!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            },function (isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('admin.history.destroy',[$asset->id, ':id']) }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $('#right-sidebar-content').html(response.view);
                                window.LaravelDataTables["assets-table"].draw();
                            }
                        }
                    });
                }
            });
        });

        function editHistory(id)
        {
            var url = '{{ route('admin.history.edit', [$asset->id, ':id'])}}';
            url = url.replace(':id', id);

            $('#subTaskModelHeading').html('Sub Task');
            $.ajaxModal('#assetModal', url);
        }

        function lend(id) {
            var url = '{{ route('admin.history.create', ':id')}}';
            url = url.replace(':id', id);
            $('#modelHeading').html("@lang('asset::app.lend')");
            $.ajaxModal('#assetModal', url);
        }
    </script>
