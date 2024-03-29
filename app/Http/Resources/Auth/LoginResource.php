<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'token' => $this->get('token'),
            'type'  => 'Bearer',
            'user'  => new UserShortResource(
                $this->get('user')
            ),
        ];
    }
}
