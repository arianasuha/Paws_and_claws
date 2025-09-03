<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;

class PetMarketUpdateRequest extends BaseRequest
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
     */
    public function rules(): array
    {
        return [
            'type' => 'sometimes|string|in:sale,adoption',
            'description' => 'sometimes|string|nullable',
            'fee' => 'sometimes|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'type.in' => 'The selected market type is invalid. It must be either "sale" or "adoption".',
        ];
    }
}