<?php

namespace App\Http\Requests\Guest;

use App\Http\Requests\AbstractRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class RefreshTokenRequest extends AbstractRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string'],
        ];
    }
}
