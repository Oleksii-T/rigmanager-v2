<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use App\Rules\ValidSlug;
use App\Enums\CategoryType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
        $model = $this->route('category');

        $rules = [
            'type' => ['required', Rule::in(CategoryType::values())],
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'fields' => ['nullable', 'array'],
            'fields.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'array', new ValidSlug(Category::class, $model->id??null)],
            'meta_title' => ['required', 'array'],
            'meta_title.en' => ['required', 'string', 'max:255'],
            'meta_description' => ['nullable', 'array'],
            'meta_description.en' => ['nullable', 'string', 'max:5000'],
            'add_desc_short' => ['nullable', 'array'],
            'add_desc_short.en' => ['nullable', 'string', 'max:5000'],
            'add_desc' => ['nullable', 'array'],
            'add_desc.en' => ['nullable', 'string', 'max:5000'],
            'home_desc' => ['nullable', 'array'],
            'home_desc.en' => ['nullable', 'string', 'max:5000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['nullable', 'bool'],
            'on_home_page' => ['nullable', 'bool'],
        ];

        if (!$this->request->get('category_id')) {
            $rules['image'] = [$model ? 'nullable' : 'required', 'image', 'max:5000'];
        }

        return $rules;
    }
}
