<?php

namespace App\Http\Controllers\Auth;

use App\GlobalSetting;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\FrontBaseController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Request;

class ResetPasswordController extends FrontBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

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

    public function showResetForm(Request $request, $token = null)
    {
        $this->global = $this->setting = GlobalSetting::first();
        $this->token = $token;

        return view('auth.passwords.reset', $this->data);
    }

    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->super_admin == '1') {
            return 'super-admin/dashboard';
        }

        if ($user->hasRole('admin')) {
            return 'admin/dashboard';
        }

        if ($user->hasRole('employee')) {
            return 'member/dashboard';
        }

        if ($user->hasRole('client')) {
            return 'client/dashboard';
        }
    }

}
