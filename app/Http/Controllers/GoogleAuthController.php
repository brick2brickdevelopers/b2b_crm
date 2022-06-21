<?php

namespace App\Http\Controllers;

use App\GoogleAccount;
use App\Services\Google;
use Illuminate\Http\Request;
use Froiden\Envato\Helpers\Reply;
use Illuminate\Support\Facades\Session;

class GoogleAuthController extends Controller
{

    public function index(Request $request, Google $google)
    {

        if ($request->access_token) {
            $googleAccount = auth()->user()->googleAccount;

            if (!$googleAccount) {
                $googleAccount = new GoogleAccount();
                $googleAccount->user_id = auth()->id();
                $googleAccount->company_id = auth()->user()->company_id;
                $googleAccount->google_id = $request->google_id;
                $googleAccount->name = $request->name;
                $googleAccount->token = json_decode($request->access_token, true);
                $googleAccount->save();
                Session::flash('message', __('app.googleCalendar').' '. __('app.account').' '. __('app.connected').' '. __('app.successfully'));
            }
            else {
                $googleAccount->user_id = auth()->id();
                $googleAccount->company_id = auth()->user()->company_id;
                $googleAccount->google_id = $request->google_id;
                $googleAccount->name = $request->name;
                $googleAccount->token = json_decode($request->access_token, true);
                $googleAccount->update();
                Session::flash('message', __('app.googleCalendar').' '. __('app.account').' '. __('app.update').' '. __('app.successfully'));
            }

            session()->forget('company_setting');
            session()->forget('company');
            return redirect()->route('admin.google-calendar.index');
        }

        if (!$request->code) {
            return redirect($google->createAuthUrl());
        }

        if ($request->state) {
            $google->authenticate($request->code);
            $account = $google->service('Oauth2')->userinfo->get();

            return redirect($request->state . '?google_id=' . $account->id . '&name=' . $account->name . '&access_token=' . json_encode($google->getAccessToken()));
        }
        
        return redirect()->route('admin.settings.index', '#googleCalendar');
    }

    public function destroy($id, Google $google)
    {
        $googleAccount = GoogleAccount::find($id);
        $googleAccount->delete();
        $google->revokeToken($googleAccount->token);

        session()->forget('company_setting');
        session()->forget('company');

        return Reply::success(__('messages.deleteSuccess'));
    }

}
