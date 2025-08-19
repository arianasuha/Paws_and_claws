<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For simplicity, we'll assume the authorization is handled by
        // a route middleware. In a real application, you might check if
        // the authenticated user's ID matches the 'reviewer' ID.
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
            'reviewer' => ['required', 'integer', 'exists:users,id'],
            'reviewee' => ['required', 'integer', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['nullable', 'string'],
            // 'review_date' is handled by the database schema as a default value
        ];
    }
}
