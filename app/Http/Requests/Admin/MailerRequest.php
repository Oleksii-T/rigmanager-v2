<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Mailer;
use Illuminate\Validation\Rule;

class MailerRequest extends FormRequest
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
        $model = $this->route('mailer');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique(Mailer::class, 'slug')->ignore($model)],
            'is_active' => ['nullable', 'boolean'],
            'user_id' => ['required', 'exists:users,id'],
            'filters' => ['required', 'array']
        ];
    }
}
