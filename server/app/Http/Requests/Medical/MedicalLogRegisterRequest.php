<?php

namespace App\Http\Requests\Medical;

use Illuminate\Foundation\Http\FormRequest;

class MedicalLogRegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'app_id' => ['required', 'integer', 'exists:appointments,id'],
            'treat_pres' => ['required', 'string'],
            'diagnosis' => ['required', 'string'],
        ];
    }
}
