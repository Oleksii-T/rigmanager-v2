<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Actions\Fortify\PasswordValidationRules;

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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique(User::class, 'slug')->ignore($model)],
            'email' => ['required', 'email', 'max:255'],
            'country' => ['required', 'string', 'max:5'],
            'avatar' => ['nullable', 'image', 'max:5000'],
            'banner' => ['nullable', 'image', 'max:5000'],
            'phone' => ['nullable', 'string'],
            'website' => ['nullable', 'string'],
            'facebook' => ['nullable', 'string'],
            'linkedin' => ['nullable', 'string'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'password' =>  $this->passwordRules(!$model),
            'roles' => ['nullable', 'array'],
            'roles.*' => ['nullable', 'exists:roles,id'],
        ];
    }
}
