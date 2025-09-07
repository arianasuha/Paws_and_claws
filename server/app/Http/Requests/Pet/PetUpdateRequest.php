<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;

class PetUpdateRequest extends BaseRequest
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
            'name' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:male,female',
            'species' => 'sometimes|string|max:255',
            'breed' => 'sometimes|string|max:255',
            'dob' => 'sometimes|date',
            'image_url' => 'sometimes|image|max:2048',
            'user_id' => 'prohibited',
            'height' => 'sometimes|numeric|min:0',
            'weight' => 'sometimes|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'user_id.prohibited' => 'You cannot modify the owner of a pet.',
        ];
    }
}