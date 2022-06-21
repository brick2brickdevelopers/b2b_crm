<div id="footer-webcall" class="row hidden-xs hidden-sm">
    <div class="col-xs-12" id="webcall-header">
        <div class="col-xs-6" style="line-height: 30px">
            <div id="call-details"></div>
            <div class="float-right">
            </div>
        </div>

        <div class="col-md-3">
            <input type="checkbox" id="callmode" {{ Auth::user()->call_mode ? 'checked' : '' }} data-toggle="toggle"
                data-onstyle="success" data-offstyle="danger" data-size="small" data-on="Auto" data-off="Manual"
                data-width="100">
        </div>
        <div class="col-xs-3">

            <a href="javascript:;" class="btn btn-default btn-circle pull-right" id="open-webcall"><i
                    class="fa fa-chevron-up"></i></a>
            <a style="display: none;" class="btn btn-default btn-circle pull-right" href="javascript:;"
                id="close-webcall"><i class="fa fa-chevron-down"></i></a>
        </div>

    </div>

    <div id="webcall-list" style="display: none">

        <div id="manualCall">
            <iframe id="hello" src="{{ route('webcall') }}" style="height: 503px; width: 320px;"
                frameborder="0"></iframe>
        </div>

    </div>
</div>
