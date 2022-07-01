<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">{{ ucfirst($platform) }} @lang('restapi::app.devices')</h4>
</div>

<div class="modal-body">
    {!! Form::open(['id'=>'sendPush','class'=>'ajax-form','method'=>'POST']) !!}
    <input type="hidden" name="platform" value="{{ $platform }}">
    <div class="form-body m-b-20">
        <div class="row">
            <div class="col-md-12">
                @if($devices->count())
                    <div class="form-group m-b-10">
                        <div class="col-md-12">
                            <label class="control-label required">@lang('restapi::app.selectDevice')</label>
                        </div>
                        @foreach($devices as $device)
                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input type="radio"
                                           class="checkbox"
                                           value="{{ $device->registration_id }}" name="device_id">{{ $device->device_id }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    @lang('restapi::app.noRegisterDeviceFound')
                @endif
            </div>
            <!--/span-->
        </div>
        <!--/row-->
    </div>
    <div class="form-actions clearfix">
        @if($devices->count())
            <button type="button" class="btn btn-success" onclick="sendPush()" id="send-test-push-submit">@lang('app.send')</button>
        @endif
        <button type="button" class="btn defaul" style="float: right" data-dismiss="modal">@lang('app.close')</button>
    </div>
    {!! Form::close() !!}
</div>