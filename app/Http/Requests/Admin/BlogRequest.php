<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidSlug;
use App\Models\Blog;
use App\Enums\BlogStatus;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
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
        $model = $this->route('blog');

        $rules = [
            'title' => ['required', 'array'],
            'title.en' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'array', new ValidSlug(Blog::class, $model->id??null)],
            'sub_title' => ['required', 'array'],
            'sub_title.en' => ['required', 'string', 'max:1000'],
            'meta_title' => ['nullable', 'array'],
            'meta_description' => ['nullable', 'array'],
            'body' => ['required', 'array'],
            'body.en' => ['required', 'string', 'max:5000'],
            'country' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'source_name' => ['nullable', 'required_with:source_link', 'string'],
            'source_link' => ['nullable', 'required_with:source_link', 'string'],
            'status' => ['required', 'string', Rule::in(BlogStatus::values())],
            'thumbnail' => ['required', 'image'],
            'images' => ['nullable', 'array'],
            // .jpg, .jpeg, .png, .bmp, .gif, .svg, .webp
            'images.*' => ['nullable', 'image', 'max:8000'],
            'documents' => ['nullable', 'array'],
            // .pdf, .xls, .xlsx, .xml, .doc, .docx
            'documents.*' => ['nullable', 'file', 'mimetypes:application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:8000']
        ];

        if ($model) {
            $rules['posted_at'] = ['required'];
            $rules['images'][0] = 'nullable';
            $rules['thumbnail'][0] = 'nullable';
        }

        return $rules;
    }
}