<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\NotificationType;

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
            'text' => ['required', 'array'],
            'type' => ['required', 'string', Rule::in(NotificationType::values())],
            'text.en' => ['required', 'string', 'max:255'],
        ];

        return $rules;
    }
}
