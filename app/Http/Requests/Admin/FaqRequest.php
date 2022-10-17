<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Faq;
use Illuminate\Validation\Rule;

class FaqRequest extends FormRequest
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
        $model = $this->route('faq');

        return [
            'question' => ['required', 'array'],
            'question.*' => ['nullable', 'string', 'max:255'],
            'question.en' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'array'],
            'answer.*' => ['nullable', 'string', 'max:5000'],
            'answer.en' => ['required', 'string', 'max:5000'],
            'slug' => ['required', 'string', 'max:255', Rule::unique(Faq::class, 'slug')->ignore($model)],
            'order' => ['required', 'integer', 'min:1', 'max:9999'],
        ];
    }
}
