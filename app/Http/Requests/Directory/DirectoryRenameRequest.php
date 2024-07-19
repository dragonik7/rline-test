<?php

namespace App\Http\Requests\Directory;

use Illuminate\Foundation\Http\FormRequest;

class DirectoryRenameRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name'      => ['required'],
        ];
    }
}
