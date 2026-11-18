<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'space_type_id' => ['sometimes', 'required', 'integer', 'exists:space_types,id'],
            'floor' => ['sometimes', 'required', 'integer', 'min:0', 'max:100'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1', 'max:1000'],
            'price_per_hour' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre no puede estar vacío',
            'space_type_id.exists' => 'El tipo de espacio no existe',
            'floor.integer' => 'El piso debe ser un número entero',
            'capacity.min' => 'La capacidad debe ser al menos 1',
            'price_per_hour.numeric' => 'El precio por hora debe ser un número',
            'price_per_hour.min' => 'El precio por hora no puede ser negativo',
            'is_active.boolean' => 'El campo activo debe ser verdadero o falso',
        ];
    }
}
