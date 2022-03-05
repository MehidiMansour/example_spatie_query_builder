<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\JsonResource;

class LogoutSuccessResponse implements Responsable
{
    public function toResponse($request)
    {
        // delete cookies related to user
        Cookie::queue(Cookie::forget('access_token'));

        return new JsonResponse('', 204);
    }
}