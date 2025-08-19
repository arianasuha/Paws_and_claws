<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Inject the Notification model instance from the route.
        // The route should be defined with route model binding, e.g.,
        // Route::patch('/notifications/{notification}', [NotificationController::class, 'update']);
        $notification = $this->route('notification');

        // Check if the authenticated user is the owner of the notification.
        return $notification && $notification->user_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'is_read' => ['required', 'boolean'],
        ];
    }
}
