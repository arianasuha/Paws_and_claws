<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'owner_id' => [
                'required',
                'integer',
                'exists:users,id', // Ensure owner_id exists in the users table
                function ($attribute, $value, $fail) {
                    if (Auth::id() !== (int) $value) {
                        $fail('You can only register pets for yourself.');
                    }
                },
            ],
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,unknown',
            'weight' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Prepare the data for validation.
     * This method automatically sets the owner_id to the authenticated user's ID.
     */
    protected function prepareForValidation(): void
    {
        if (Auth::check()) {
            $this->merge([
                'owner_id' => Auth::id(),
            ]);
        }
    }
}