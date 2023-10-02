<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\NotificationType;
use App\Enums\NotificationGroup;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'string', Rule::in(NotificationType::values())],
            'group' => ['required', 'string', Rule::in(NotificationGroup::values())],
            'notifiable_id' => ['nullable', 'integer'],
            'notifiable_type' => ['nullable', 'string', 'max:255'],
            'data' => ['nullable', 'string'],
        ];

        return $rules;
    }
}
