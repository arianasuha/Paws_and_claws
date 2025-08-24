<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;

class ReportLostPetUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'location' => ['sometimes', 'string', 'max:255'],
            'date_lost' => ['sometimes', 'date'],
            'status' => ['sometimes', 'string', 'in:found'],
        ];
    }
}
