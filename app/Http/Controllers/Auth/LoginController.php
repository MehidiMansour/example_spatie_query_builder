<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Resources\Auth\LoginFailedResponse;
use App\Http\Resources\Auth\LoginSuccessResponse;

class LoginController extends Controller
{
    use ThrottlesLogins;
    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return LoginSuccessResponse|LoginFailedResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        // Automatically throttle the login attempts for this application,
        // We'll key this by the username and the IP address of the client.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        /** @var User $user */
        $user = User::where($this->username(), $request->get($this->username()))->first();

        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            $this->incrementLoginAttempts($request);

            return new LoginFailedResponse($user);
        }

        return new LoginSuccessResponse($user);
    }
    /**
     * Get the login username to be used by the controller.
     */
    public function username(): string
    {
        return 'email';
    }
}