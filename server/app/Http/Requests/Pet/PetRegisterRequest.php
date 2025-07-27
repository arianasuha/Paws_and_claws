<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PetRegisterRequest extends BaseRequest
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
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'dob' => 'required|date',
            'image_url' => 'nullable|image|max:2048',
            'user_id' => 'prohibited', // Prevent user_id from being sent
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'user_id.prohibited' => 'You can only register pets for yourself.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}