<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;

class ReportLostPetRegisterRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'location' => ['required', 'string', 'max:255'],
            'date_lost' => ['required', 'date'],
            'status' => ['nullable', 'string', 'in:missing,found'],
        ];
    }
}