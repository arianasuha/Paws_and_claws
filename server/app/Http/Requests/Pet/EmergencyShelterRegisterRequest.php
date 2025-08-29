<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;

class EmergencyShelterRegisterRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Still a good idea to ensure an authenticated user is making the request
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'request_date' => ['sometimes', 'date'],
        ];
    }
}