<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:9000'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'string', Rule::in(Post::TYPES)],
            'condition' => ['nullable', 'string', Rule::in(Post::CONDITIONS)],
            'is_urgent' => ['nullable', 'boolean'],
            'amount' => ['nullable', 'string', 'max:70'],
            'country' => ['required', 'string', 'max:2'],
            'manufacturer' => ['nullable', 'string', 'max:70'],
            'manufacture_date' => ['nullable', 'string', 'max:70'],
            'part_number' => ['nullable', 'string', 'max:70'],
            'cost_per' => ['nullable', 'string', 'max:255'],
            'is_double_cost' => ['nullable', 'boolean'],
            'is_tba' => ['nullable', 'boolean'],
            'currency' => ['nullable', 'required_with:cost'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'max:8000'], // .jpg, .jpeg, .png, .bmp, .gif, .svg, .webp
            'old_images' => ['nullable', 'array'],
            'old_images.*' => ['nullable', 'exists:attachments,id'],
            'documents' => ['nullable', 'array'],
            'documents.*' => [
                'nullable',
                'file',
                'mimetypes:application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'max:8000'
            ] // .pdf, .xls, .xlsx, .xml, .doc, .docx
        ];

        if ($this->is_double_cost) {
            $rules['cost_from'] = ['nullable', 'required_with:cost_to', 'numeric', 'min:1', 'max:9999999', 'lt:cost_to'];
            $rules['cost_to'] = ['nullable', 'required_with:cost_from', 'numeric', 'min:1', 'max:9999999', 'gt:cost_from'];
        } else {
            $rules['cost'] = ['nullable', 'numeric', 'min:1', 'max:9999999'];
        }

        return $rules;
    }
}
