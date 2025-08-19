<?php

namespace App\Http\Requests;

use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Validation\Rule;

class ServiceProviderUpdateRequest extends UpdateUserRequest
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
        // Get the rules from the parent UpdateUserRequest
        $parentRules = parent::rules();

        // Define the rules specific to a Service Provider, all marked as 'sometimes'
        // since they are optional for an update.
        $serviceProviderRules = [
            'service_type' => ['sometimes', 'string', Rule::in(['walker', 'groomer', 'trainer'])],
            'service_desc' => ['sometimes', 'nullable', 'string'],
            'rate_per_hour' => ['sometimes', 'numeric'],
            'rating' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:5'],
        ];

        // Merge the parent rules with the specific service provider rules
        return array_merge($parentRules, $serviceProviderRules);
    }
}
