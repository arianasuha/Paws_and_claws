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
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($userId)],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed', new StrongPassword()],
            'clinic_name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'services_offered' => ['nullable', 'string'],
            'working_hour' => ['nullable', 'string', 'max:255'],
        ];

    }
}