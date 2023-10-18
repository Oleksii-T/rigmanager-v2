<?php

namespace App\Http\Requests;

use App\Rules\ImportValidation;
use Illuminate\Validation\Rule;
use App\Rules\InitialImportFileValidation;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
            'file' => ['required', 'file', 'mimes:xlsx', new InitialImportFileValidation],
            'start_row' => ['required', 'numeric', 'min:1', 'max:9999'],
            'end_row' => ['required', 'numeric', 'min:1', 'max:9999'],
            'columns' => ['required', 'array', new ImportValidation],
        ];
    }
}
