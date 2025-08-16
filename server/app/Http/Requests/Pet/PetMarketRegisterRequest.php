<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PetMarketRegisterRequest extends FormRequest
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
            'pet_id' => 'required|exists:pets,id',
            'date' => 'required|date',
            'type' => 'required|in:sell,adopt,breed',
            'status' => 'required|in:active,completed,canceled',
            'description' => 'nullable|string',
            'fee' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'pet_id.exists' => 'The selected pet does not exist.',
            'type.in' => 'The selected type is invalid.',
            'status.in' => 'The selected status is invalid.',
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