<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueSlug;
use App\Models\Category;

class CategoryRequest extends FormRequest
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
        return [
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'array', new UniqueSlug(Category::class)],
            'slug.en' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:5000'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }
}
