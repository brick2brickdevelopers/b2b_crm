<?php

namespace App\Http\Controllers\Auth;

use App\GlobalSetting;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\FrontBaseController;
use App\Traits\SmtpSettings;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends FrontBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails, SmtpSettings;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        $this->global = $this->setting = GlobalSetting::first();
        return view('auth.passwords.email', $this->data);
    }

}
