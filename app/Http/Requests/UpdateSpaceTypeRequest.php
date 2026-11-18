<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSpaceTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('space_types', 'name')->ignore($this->route('spaceType')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del tipo es obligatorio',
            'name.unique' => 'Ya existe un tipo de espacio con ese nombre',
        ];
    }
}