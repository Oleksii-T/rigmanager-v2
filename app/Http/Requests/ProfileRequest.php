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
            'avatar' => ['nullable', 'image', 'max:5000'],
            'banner' => ['nullable', 'image', 'max:5000'],
            'info.website' => ['nullable', 'string', 'max:255'],
            'info.facebook' => ['nullable', 'string', 'max:255'],
            'info.linkedin' => ['nullable', 'string', 'max:255'],
            'info.whatsapp' => ['nullable', 'string', 'max:255'],
            'info.bio' => ['nullable', 'string', 'max:5000'],
            'info.phones' => ['nullable', 'array'],
            'info.phones.*' => ['nullable', 'string', 'max:30', 'regex:/^[\+\d\-\ \(\)]{8,}$/'],
            'info.emails' => ['nullable', 'array'],
            'info.emails.*' => ['nullable', 'email', 'max:255'],
        ];
    }
}
