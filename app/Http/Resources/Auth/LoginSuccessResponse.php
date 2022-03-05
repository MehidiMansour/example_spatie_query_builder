<?php

namespace App\Http\Resources\Auth;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginSuccessResponse extends JsonResource
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function toResponse($request)
    {
        // Revoke all previous tokens...
        $this->user->tokens()->where('name', 'Authentication')->delete();

        // Create new token
        $token = $this->user->createToken('Authentication');

        return response([
            'token_type' => 'Bearer',
            'access_token' => $token->plainTextToken,
        ]);
    }
}