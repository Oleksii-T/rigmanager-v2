<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidSlug;
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
        $model = $this->route('category');
        $rules = [
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'array', new ValidSlug(Category::class, $model->id??null)],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];

        if (!$this->request->get('category_id')) {
            $rules['image'] = [$model ? 'nullable' : 'required', 'image', 'max:5000'];
        }

        return $rules;
    }
}
