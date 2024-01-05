<?php

namespace App\Services\Api\Auth;

use App\Http\Resources\Auth\LoginResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LoginCommand
{
    public function handle(LoginDto $dto): JsonResponse|LoginResource
    {
        try {
            if (
                Auth::attempt([
                    'email'    => $dto->getEmail(),
                    'password' => $dto->getPassword(),
                ])
            ) {
                return LoginResource::make($this->authenticationPassedResponse());
            }
        } catch (Exception $exception) {
            return $this->authenticationFailedResponse($exception->getMessage());
        }

        return $this->authenticationFailedResponse(__('auth.failed'));
    }

    /**
     * @return array
     */
    private function authenticationPassedResponse(): Collection
    {
        return collect(
            [
                'token' => $this->setToken(auth()->user()),
                'user'  => auth()->user(),
            ]
        );
    }

    /**
     * Authentication failed response.
     *
     * @param string $message
     * @param int    $status
     *
     * @return JsonResponse
     */
    private function authenticationFailedResponse(
        string $message,
        int $status = 403
    ): JsonResponse {
        return response()
            ->json([
                'success' => false,
                'message' => $message,
            ], $status);
    }

    /**
     * @param User $user
     *
     * @return string
     */
    private function setToken(User $user): string
    {
        $user->tokens()
            ->delete();

        return $user->createToken('api')->plainTextToken;
    }
}
