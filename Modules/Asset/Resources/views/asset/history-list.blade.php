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
                <span class="text-muted font-12">{{ $history->date_given->setTimezone($global->timezone)->format('d F Y H:i A') }} ({{ $history->date_given->setTimezone($global->timezone)->diffForHumans(\Carbon\Carbon::now()->setTimezone($global->timezone)) }})</span>
            </div>
        </div>
        <div class="col-xs-12  m-b-10">
            <div class="col-xs-3">
                <span class="text-dark font-12">@lang('asset::app.returnDate')</span>
            </div>
            <div class="col-xs-9">
                <span class="text-muted font-12">{{ !is_null($history->return_date) ? ucwords($history->return_date->setTimezone($global->timezone)->format('d F Y H:i A')). ' ('.$history->return_date->setTimezone($global->timezone)->diffForHumans(\Carbon\Carbon::now()->setTimezone($global->timezone)) .')' : '-' }}</span>
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
        <div  class="text-center">
            <div class="empty-space" style="height: 200px;">
                <div class="empty-space-inner">
                    <div class="icon" style="font-size:30px"><i
                                class="icon-layers"></i>
                    </div>
                    <div class="title m-b-15">
                        @lang('messages.noRecordFound')
                    </div>

                </div>
            </div>
        </div>

    </div>
@endforelse
