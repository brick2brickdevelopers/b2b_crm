<?php

namespace Modules\RestAPI\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Froiden\RestAPI\ApiController;
use Froiden\RestAPI\ApiResponse;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\RestAPI\Http\Requests\Auth\EmailVerifyRequest;
use Modules\RestAPI\Http\Requests\Auth\ForgotPasswordRequest;
use Modules\RestAPI\Http\Requests\Auth\LoginRequest;
use Modules\RestAPI\Http\Requests\Auth\LogoutRequest;
use Modules\RestAPI\Http\Requests\Auth\RefreshTokenRequest;
use Modules\RestAPI\Http\Requests\Auth\ResendVerificationMailRequest;
use Modules\RestAPI\Http\Requests\Auth\ResetPasswordRequest;

class AuthController extends ApiBaseController
{


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function login(LoginRequest $request)
    {
        // Modifications to this function may also require modifications to
        $email = $request->get('email');
        $password = $request->get('password');
        $days =  365;
        $minutes =  60 * 60 * $days;
        config()->set('jwt.ttl', $minutes);
        $claims = ['exp' => (int)Carbon::now()->addYear()->getTimestamp(), 'remember' => 1, 'type' => 1];

        $token = auth()->claims($claims)->attempt(['email' => $email, 'password' => $password]);

        if ($token) {
            $user = User::where('email', $email)->first();

            if ($user && $user->status === 'deactive') {
                $exception = new ApiException('User account disabled', null, 403, 403, 2015);
                return ApiResponse::exception($exception);
            }

            /** @var Admin $user */
            $user = auth()->user();
            //          $payload = auth()->payload();

            $expire = \Carbon\Carbon::now()->addYear(1);
            return ApiResponse::make('Logged in successfully', [
                'token' => $token,
                'user' => $user->load('roles', 'roles.perms', 'roles.permissions'),
                'expires' => $expire,
                'expires_in' => auth()->factory()->getTTL(),
            ]);
        }

        $exception = new ApiException('Wrong credentials provided', null, 403, 403, 2001);
        return ApiResponse::exception($exception);
    }

    public function logout(LogoutRequest $request)
    {
        auth()->invalidate();
        return ApiResponse::make('Token invalidated successfully');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->email;

        $user = User::where('email', $email)->first();

        if ($user) {
            $code = Str::random(60);
            $user->password_reset_token = $code;
            $user->save();

            dispatch(new SendForgotPasswordEmail($user));
        }

        return ApiResponse::make('If your email belongs to an account, a password reset email has been sent to it');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $passwordResetToken = $request->password_reset_token;
        $password = $request->password;

        $user = Employee::where('password_reset_token', $passwordResetToken)->first();
        $hash = \Hash::make($password);
        $user->password = $hash;
        $user->password_reset_token = null;
        $user->save();

        return ApiResponse::make('Password reset successful');
    }

    public function refresh(RefreshTokenRequest $request)
    {
        config([
            'jwt.blacklist_enabled' => false
        ]);
        try {
            $newToken = auth()->refresh();
            $payload = auth()->payload();
            $user = auth()->user();
            $expire = Carbon::createFromTimestamp($payload('exp'))->format('Y-m-d\TH:i:sP');


            if ($user->status === 'deactive') {
                throw new ApiException('User account disabled', null, 403, 403, 2015);
            }

            return ApiResponse::make('Token refreshed successfully', [
                'token' => $newToken,
                'expires' => $expire
            ]);
        } catch (ApiException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ApiException('Provided token is invalid', $e, 403, 403, 2003);
        }
    }


    public function verify(EmailVerifyRequest $request)
    {

        $user = Employee::where('email_verification_token', $request->token)
            ->whereNotNull('email_verification_token')
            ->first();

        if ($user) {
            DB::beginTransaction();

            $user->email_verification_token = null;
            $user->email_verified = 'yes';
            $user->save();

            $user->company->company_email_verified = 'yes';
            $user->company->save();

            event(new EmailVerificationSuccessEvent($user->company, $user));
            DB::commit();

            return ApiResponse::make('Success', ['status' => 'success']);
        }

        return ApiResponse::make('Token is expired', ['status' => 'fail']);
    }

    public function resendVerifyMail(ResendVerificationMailRequest $request)
    {
        $user = Employee::where('email', $request->email)->first();

        if ($user) {
            $user->email_verification_token = str_random(40);
            $user->save();

            event(new ResendVerificationEmailEvent($user->company, $user));

            return ApiResponse::make('Verification mail successfully send', ['status' => 'success']);
        }

        throw new ApiException('Your provided email does not exists.', null, 403, 403, 2001);
    }

    public function me()
    {
        return ApiResponse::make('Auth User', [
            'data' => api_user()->load('roles', 'roles.perms', 'roles.permissions'),
        ]);
    }
}
