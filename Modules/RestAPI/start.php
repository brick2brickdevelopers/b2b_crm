<?php

/*
|--------------------------------------------------------------------------
| Register Namespaces And Routes
|--------------------------------------------------------------------------
|
| When a module starting, this file will executed automatically. This helps
| to register some namespaces like translator or view. Also this file
| will load the routes file for each module. You may also modify
| this file as you want.
|
*/

use Froiden\RestAPI\Exceptions\UnauthorizedException;

require __DIR__ . '/Routes/api.php';

if (!function_exists('parseUser')) {

    function parseUser()
    {
        config(['auth.defaults.guard'=>'api']);
        config(['jwt.secret' => config('restapi.jwt_secret')]);

        try {

            if (isRunningInBrowser()) {

                $user = auth()->user();

                if ($user === false) {
                    $exception =  new \Froiden\RestAPI\Exceptions\UnauthorizedException('User not found', null, 403, 403, 2006);
                    return \Froiden\RestAPI\ApiResponse::exception($exception);
                }
                return $user;

            }

            return null;

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            if (isRunningInBrowser()) {
                throw new \Froiden\RestAPI\Exceptions\UnauthorizedException('Token has expired', null, 403, 403, 2007);
            }

            return null;

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            if (isRunningInBrowser()) {
                $exception =  new \Froiden\RestAPI\Exceptions\UnauthorizedException('Token is invalid', null, 403, 403, 2008);
                return \Froiden\RestAPI\ApiResponse::exception($exception);
            }

            return null;

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return null;
        }
    }

}


if (!function_exists('api_user')) {

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Http\Response|null
     * @throws UnauthorizedException
     */
    function api_user()
    {
        $user = parseUser();
        return is_a($user, '\App\User') || is_a($user, '\Modules\RestAPI\Entities') ? $user : null;
    }

}

if (!function_exists('isRunningInBrowser')) {

    /**
     * Check if app is running in browser
     * @return boolean
     */
    function isRunningInBrowser()
    {
        // App should not be running in console, or if it is, then it should
        // be running unit tests. We need to check browser running because
        // we want to prevent some parts getting executed in seeders, migrations, etc.
        // which are run in console.
        return !App::runningInConsole() || (App::runningInConsole() && App::runningUnitTests());
    }

}

if (!function_exists('isSeedingData')) {

    /**
     * Check if app is seeding data
     * @return boolean
     */
    function isSeedingData()
    {
        // We set $_ENV['SEEDING'] at the begining of each seeder. And check here
        return isset($_ENV['SEEDING']) && $_ENV['SEEDING'] == true;
    }

}

if (!function_exists('isRunningTests')) {

    /**
     * Check if app is running unit tests
     * @return boolean
     */
    function isRunningTests()
    {
        // If app env is testing
        return env('APP_ENV') == 'testing';
    }

}

if (!function_exists('shouldSendMail')) {

    /**
     * Check if app is running unit tests
     * @return boolean
     */
    function shouldSendMail()
    {
        // If app is not seeding data and not running unit tests
        return !isSeedingData() && !isRunningTests();
    }

}


