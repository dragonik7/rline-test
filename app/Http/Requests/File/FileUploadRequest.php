<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'files.*'   => ['required', 'file', 'max:10240'],
            'directory' => ['integer', 'exists:App\Models\Directory,id', 'nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
