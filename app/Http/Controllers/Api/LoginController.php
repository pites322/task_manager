<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Services\Api\Auth\LoginCommand;

use function auth;

use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;

/**
 * @group Admin/Auth
 */
class LoginController extends Controller
{
    /**
     * @param \App\Http\Requests\Api\Auth\LoginRequest $request
     * @param \App\Services\Api\Auth\LoginCommand      $loginCommand
     *
     * @return \App\Http\Resources\Auth\LoginResource|\Illuminate\Http\JsonResponse
     */
    public function login(
        LoginRequest $request,
        LoginCommand $loginCommand,
    ): JsonResponse|LoginResource {
        return $loginCommand->handle($request->getDto());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    #[Authenticated]
    public function logout(): JsonResponse
    {
        $user = auth()->user();

        return response()->json([
            'data' => [
                'success' => (bool)$user->currentAccessToken()->delete(),
            ],
        ]);
    }
}
