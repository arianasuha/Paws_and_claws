<?php

namespace App\Http\Requests;
use App\Http\Requests\BaseRequest;

class AppointmentRegisterRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'pet_id' => 'required|exists:pets,id',
            'provider_id' => 'required|exists:users,id',
            'app_date' => 'required|date',
            'app_time' => 'required',
            'visit_reason' => 'required|string',
        ];
    }
}
