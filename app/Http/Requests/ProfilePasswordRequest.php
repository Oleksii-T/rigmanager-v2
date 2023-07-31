<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CurrentPassword;
use App\Actions\Fortify\PasswordValidationRules;

class ProfilePasswordRequest extends FormRequest
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
        $rules = [
            'password' => $this->passwordRules()
        ];
        
        if (auth()->user()->password) {
            $rules['current_password'] = ['required', 'string', 'max:255', new CurrentPassword()];
        }

        return $rules;
    }
}
