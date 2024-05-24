<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ScraperRequest extends FormRequest
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
        $model = $this->route('scraper');

        return [
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,id'],
            'base_urls' => ['required', 'string'],
            'scrape_limit' => ['required', 'numeric', 'min:1'],
            'sleep' => ['required', 'numeric', 'min:1'],
            'with_cache' => ['required', 'bool'],
            'is_debug' => ['required', 'bool'],
            'selectors' => ['required', 'array'],
        ];
    }
}
