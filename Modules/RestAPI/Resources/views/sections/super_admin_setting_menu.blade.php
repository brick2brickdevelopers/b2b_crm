<li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.rest-api-setting.index') active @endif">
    <a href="{{ route('super-admin.rest-api-setting.index') }}" class="waves-effect"><span class="hide-menu"> @lang('restapi::app.menu.restAPISettings')</span></a>
</li>