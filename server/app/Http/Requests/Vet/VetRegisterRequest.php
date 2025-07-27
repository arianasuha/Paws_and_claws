<?php

namespace App\Http\Requests\Vet;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class VetRegisterRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->is_admin || $this->input('user_id') == Auth::id());
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
                Rule::unique('vets', 'user_id'),
            ],
            'clinic_name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'services_offered' => ['nullable', 'string'],
            'working_hour' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.unique' => 'This user already has a vet profile.',
            'user_id.in' => 'You can only create a vet profile for your own user ID (unless you are an admin).',
        ];
    }
}