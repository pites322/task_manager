<?php

namespace App\Http\Requests\Api\Auth;

use App\Services\Api\Auth\LoginDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'    => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                Password::min(6),
                'max:24',
            ],
        ];
    }

    public function getDto(): LoginDto
    {
        return new LoginDto(
            $this->get('email'),
            $this->get('password'),
        );
    }
}
