<?php

namespace App\Http\Controllers;

use App\FrontDetail;
use Carbon\Carbon;
use Froiden\Envato\Traits\AppBoot;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cookie;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, AppBoot;

    public function __construct()
    {
        $this->showInstall();

        $this->checkMigrateStatus();

        $this->middleware(function ($request, $next) {
            $this->superadmin = global_settings();
            App::setLocale($this->superadmin->locale);

            $this->frontDetail    = FrontDetail::first();
            $this->global = global_settings();
            $this->superadmin = global_settings();
            $this->user = null;
            if(!is_null(auth()->user())){
                $this->user = auth()->user();
            }

            if (Cookie::get('language')) {
                try{
                    // $langArray = explode('|', decrypt(Cookie::get('language'), false));
                    // $this->locale = isset($langArray[1]) ? $langArray[1] : $langArray[0];
                    $this->locale = Cookie::get('language');
                    App::setLocale($this->locale);
                }
                catch(\Exception $e){
                    $this->locale = $this->superadmin->locale;
                    App::setLocale($this->locale);
                }
            } else {
                if(!is_null(auth()->user())){
                    $this->locale = auth()->user()->locale;
                }
                else{
                    $this->locale = $this->frontDetail->locale;
                    App::setLocale($this->locale);

                }
            }

            config(['app.name' => $this->global->company_name]);
            config(['app.url' => url('/')]);

            App::setLocale($this->superadmin->locale);
            Carbon::setLocale($this->superadmin->locale);
            setlocale(LC_TIME, 'en' . '_' . strtoupper('en'));

            $user = auth()->user();
            if ($user && $user->super_admin == 1) {
                config(['froiden_envato.allow_users_id' => true]);
            }

            if (config('app.env') !== 'development') {
                config(['app.debug' => $this->superadmin->app_debug]);
            }

            return $next($request);
        });

    }

    public function checkMigrateStatus()
    {
        $status = Artisan::call('migrate:check');

        if ($status && !request()->ajax()) {
            Artisan::call('migrate', array('--force' => true)); //migrate database
            Artisan::call('optimize:clear');
        }
    }

}
