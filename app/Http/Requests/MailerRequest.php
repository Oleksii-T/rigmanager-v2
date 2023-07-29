<?php

namespace App\Http\Requests;

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

        if ($model) {
            return [
                'title' => ['required', 'string', 'max:255'],
                'filters' => ['required', 'array']
            ];
        }

        return [
            'filters' => ['required', 'array']
        ];
    }
}
