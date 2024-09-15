<?php

namespace App\Http\Requests\Guest;

use App\Http\Requests\AbstractRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends AbstractRequest
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
            'email' => ['string', 'max:255', 'unique:users,email','email:filter'],
            'username' => [
                'max:255',
                'regex:/^[a-zA-Z0-9_-]+$/',
                Rule::notIn(['_', '-']),
                'unique:users,username'
            ],
            'password' => ['min:8','confirmed'],
            'current_password' => ['required_with:password', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'full_name' => ['string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'bio' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already in use.',
            'email.email' => 'Please provide a valid email address.',
            'username.unique' => 'This username is already taken.',
            'username.regex' => 'The username can only contain letters, numbers, underscores, and dashes.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.confirmed' => 'Password does not match.',
            'current_password.required_with' => 'The current password is required when changing the password.',
            'current_password.check' => 'The current password is incorrect.',
            'full_name.regex' => 'The full name may only contain letters and spaces.',
            'bio.string' => 'The bio must be a string.',
        ];
    }
}
