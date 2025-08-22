<?php

namespace App\Http\Requests;

use App\Http\Requests\User\RegisterUserRequest;
use Illuminate\Validation\Rule;

class ServiceProviderRegisterRequest extends RegisterUserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Inherits authorization from the parent request.
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the rules from the parent RegisterUserRequest
        $parentRules = parent::rules();

        // Define the rules specific to a Service Provider
        $serviceProviderRules = [
            'service_type' => ['required', 'string', Rule::in(['walker', 'groomer', 'trainer'])],
            'service_desc' => ['nullable', 'string'],
            'rate_per_hour' => ['required', 'numeric'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
        ];

        // Merge the parent rules with the specific service provider rules
        return array_merge($parentRules, $serviceProviderRules);
    }
}

