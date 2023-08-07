<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:9000'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'string', Rule::in(Post::TYPES)],
            'condition' => ['nullable', 'string', Rule::in(Post::CONDITIONS)],
            'duration' => ['required', 'string', Rule::in(Post::DURATIONS)],
            'is_urgent' => ['nullable', 'boolean'],
            'amount' => ['nullable', 'integer', 'min:1', 'max:9999999'],
            'country' => ['required', 'string', 'max:2'],
            'manufacturer' => ['nullable', 'string', 'max:70'],
            'manufacture_date' => ['nullable', 'string', 'max:70'],
            'part_number' => ['nullable', 'string', 'max:70'],
            'cost' => ['nullable', 'numeric', 'min:1', 'max:9999999'],
            'currency' => ['nullable', 'required_with:cost'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'max:8000'], // .jpg, .jpeg, .png, .bmp, .gif, .svg, .webp
            'documents' => ['nullable', 'array'],
            'documents.*' => [
                'nullable',
                'file',
                'mimetypes:application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'max:8000'
            ] // .pdf, .xls, .xlsx, .xml, .doc, .docx
        ];
    }
}
