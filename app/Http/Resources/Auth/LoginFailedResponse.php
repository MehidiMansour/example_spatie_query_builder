<?php

namespace App\Http\Resources\Auth;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginFailedResponse implements Responsable
{
    private ?User $user;

    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    /**
     * @throws ValidationException
     */
    public function toResponse($request)
    {
        throw ValidationException::withMessages(['email' => [trans('auth.failed')]]);
    }
}