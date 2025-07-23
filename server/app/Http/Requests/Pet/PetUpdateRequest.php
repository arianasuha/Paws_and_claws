<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Pet;

class PetUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $petId = $this->route('pet');

        $pet = Pet::find($petId);

        if (!$pet) {
            return false;
        }

        return Auth::check() && Auth::id() === $pet->owner_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'species' => 'nullable|string|max:255',
            'breed' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,unknown',
            'weight' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}