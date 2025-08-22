<?php

namespace App\Http\Requests\Medical;

use Illuminate\Foundation\Http\FormRequest;

class MedicalLogUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'app_id' => ['sometimes', 'integer', 'exists:appointments,id'],
            'treat_pres' => ['sometimes', 'string'],
            'diagnosis' => ['sometimes', 'string'],
        ];
    }
}
