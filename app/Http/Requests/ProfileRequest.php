<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
        $model = auth()->user();

        return [
            'name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:5'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($model->id)
            ],
            'phone' => ['nullable', 'string', 'max:40'],
            'avatar' => ['nullable', 'image', 'max:5000']
        ];
    }
}
