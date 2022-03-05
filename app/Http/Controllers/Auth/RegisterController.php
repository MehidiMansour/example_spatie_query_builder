<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\Auth\LoginSuccessResponse;

class RegisterController extends Controller
{
    /**
         * Handle a registration request for the application.
         * @param UserRegisterRequest $request
         * @return LoginSuccessResponse
         * @throws Throwable
         */
        public function register(UserRegisterRequest $request): LoginSuccessResponse
        {
            $validated = $request->validated();
            $user = $this->create($validated);

            return new LoginSuccessResponse($user);
        }
    /**
     * Create a new user instance after a valid registration.
     */
    private function create(array $data): User
    {
        // creating a user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        return $user;
    }
}