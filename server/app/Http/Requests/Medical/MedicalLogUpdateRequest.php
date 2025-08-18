<?php

namespace App\Http\Requests\MedicalLog;

use Illuminate\Foundation\Http\FormRequest;

class MedicalLogUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can add authentication logic here if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'app_id' => ['sometimes', 'integer', 'exists:appointments,id'],
            'treat_pres' => ['sometimes', 'string'],
            'diagnosis' => ['sometimes', 'string'],
        ];
    }
}
