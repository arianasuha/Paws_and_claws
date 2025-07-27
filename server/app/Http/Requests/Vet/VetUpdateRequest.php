<?php

namespace App\Http\Requests\Vet;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class VetUpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vetId = $this->route('vet');

        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
            ],
            'clinic_name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'services_offered' => ['required', 'string'],
            'working_hour' => ['required', 'string', 'max:255'],
        ];
    }
}