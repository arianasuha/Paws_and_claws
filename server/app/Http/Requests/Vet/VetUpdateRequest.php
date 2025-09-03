<?php

namespace App\Http\Requests\Vet;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use App\Rules\StrongPassword;

class VetUpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('vet');
        
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'username' => ['sometimes', 'string', 'max:255', Rule::unique('users', 'username')->ignore($userId)],
            'address' => ['sometimes', 'string', 'max:255'],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed', new StrongPassword()],
            'clinic_name' => ['sometimes', 'string', 'max:255'],
            'specialization' => ['sometimes', 'string', 'max:255'],
            'services_offered' => ['sometimes', 'string'],
            'working_hour' => ['sometimes', 'string', 'max:255'],
        ];

    }

    public function messages(): array
    {
        return [
            'email.unique' => 'The email address is already in use by another user.',
            'username.unique' => 'The username is already taken by another user.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}