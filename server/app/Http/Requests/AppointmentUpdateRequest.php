<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class AppointmentUpdateRequest extends BaseRequest
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
            'status' => 'string|required|in:accepted,completed,canceled',
        ];
    }
}
