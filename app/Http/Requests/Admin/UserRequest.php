<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Actions\Fortify\PasswordValidationRules;
use Laravel\Fortify\Rules\Password;

class UserRequest extends FormRequest
{
    use PasswordValidationRules;

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
        $model = $this->route('user');

        return [
            'user.name' => ['required', 'string', 'max:255'],
            'user.slug' => ['required', 'string', 'max:255', Rule::unique(User::class, 'slug')->ignore($model)],
            'user.email' => ['required', 'email', 'max:255', $model ? Rule::unique(User::class, 'email')->ignore($model) : 'unique:users,email'],
            'user.country' => ['required', 'string', 'max:5'],
            'user.avatar' => ['nullable', 'image', 'max:5000'],
            'user.banner' => ['nullable', 'image', 'max:5000'],
            'user.password' => [$model ? 'nullable' : 'required', 'string', new Password],
            'user.roles' => ['nullable', 'array'],
            'user.roles.*' => ['nullable', 'exists:roles,id'],
            'user.status' => ['nullable', 'string'],

            'info.website' => ['nullable', 'string'],
            'info.facebook' => ['nullable', 'string'],
            'info.linkedin' => ['nullable', 'string'],
            'info.bio' => ['nullable', 'string', 'max:5000'],
            'info.emails' => ['nullable', 'json'],
            'info.phones' => ['nullable', 'json'],

            'verify_email_now' => ['required', 'integer'],
        ];
    }
}
