<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidSlug;
use App\Models\Post;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
        $model = $this->route('post');

        return [
            'title' => ['required', 'array'],
            'title.en' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'array', new ValidSlug(Post::class, $model->id??null)],
            'description' => ['required', 'array'],
            'description.en' => ['required', 'string', 'max:5000'],
            'user_id' => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'origin_lang' => ['required', 'string'],
            'status' => ['required', 'string', Rule::in(Post::STATUSES)],
            'type' => ['required', 'string', Rule::in(Post::TYPES)],
            'condition' => ['required', 'string', Rule::in(Post::CONDITIONS)],
            'duration' => ['required', 'string', Rule::in(Post::DURATIONS)],
            'is_active' => ['nullable', 'boolean'],
            'is_urgent' => ['nullable', 'boolean'],
            'amount' => ['nullable', 'integer', 'min:1', 'max:9999999'],
            'country' => ['required', 'string', 'max:5'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'manufacture_date' => ['nullable', 'string', 'max:255'],
            'part_number' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric', 'min:1', 'max:9999999'],
            'currency' => ['nullable', 'required_with:cost'],
            'images' => ['nullable', 'array'],
            // .jpg, .jpeg, .png, .bmp, .gif, .svg, .webp
            'images.*' => ['nullable', 'image', 'max:8000'],
            'documents' => ['nullable', 'array'],
            // .pdf, .xls, .xlsx, .xml, .doc, .docx
            'documents.*' => ['nullable', 'file', 'mimetypes:application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:8000']
        ];
    }
}
