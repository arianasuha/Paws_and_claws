<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For simplicity, we'll assume the authorization is handled by
        // a route middleware. You should implement a check here to ensure
        // the authenticated user is the one who created the review.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'reviewer' => ['sometimes', 'integer', 'exists:users,id'],
            'reviewee' => ['sometimes', 'integer', 'exists:users,id'],
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'review_text' => ['nullable', 'string'],
        ];
    }
}
