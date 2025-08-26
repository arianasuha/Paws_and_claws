<?php

namespace App\Http\Requests\EmergencyShelter;

use App\Http\Requests\BaseRequest;

class EmergencyShelterRegisterRequest extends BaseRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pet_id' => 'required|integer|exists:pets,id',
            'request_date' => 'required|date',
        ];
    }
}
