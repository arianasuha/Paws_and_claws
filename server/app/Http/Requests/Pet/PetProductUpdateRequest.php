<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PetProductUpdateRequest extends BaseRequest
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
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('pet_products')->ignore($this->route('petProductid'))],
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'image_url' => 'sometimes|image|max:2048',
            'category_id' => 'sometimes|exists:categories,id',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'A product with this name already exists.',
            'category_id.exists' => 'The selected category does not exist.',
            'image_url.image' => 'The product image must be an image.',
            'image_url.mimes' => 'The product image must be a JPEG, PNG, JPG, or GIF image.',
            'image_url.max' => 'The product image size must not exceed 2MB.',
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