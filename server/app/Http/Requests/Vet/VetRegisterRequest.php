<?php

namespace App\Http\Requests\Vet;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class VetRegisterRequest extends RegisterUserRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $parentRules = parent::rules();

        $vetRules = [
            'clinic_name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'services_offered' => ['nullable', 'string'],
            'working_hour' => ['nullable', 'string', 'max:255'],
        ];

        return array_merge($parentRules, $vetRules);
    }
}