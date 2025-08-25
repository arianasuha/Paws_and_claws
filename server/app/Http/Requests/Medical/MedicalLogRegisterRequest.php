<?php

namespace App\Http\Requests\Medical;
use App\Http\Requests\BaseRequest;

class MedicalLogRegisterRequest extends BaseRequest
{

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
            'app_id' => ['required', 'exists:appointments,id'],
            'treat_pres' => ['required', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],

            'pet_id' => ['required', 'exists:pets,id'],
        ];
    }
}
