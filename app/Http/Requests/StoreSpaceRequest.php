<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // los permisos se validan con el middleware de la ruta
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'space_type_id' => ['required', 'integer', 'exists:space_types,id'],
            'floor' => ['required', 'integer', 'min:0', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1', 'max:1000'],
            'price_per_hour' => ['required', 'numeric', 'min:0', 'max:99999'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'space_type_id.required' => 'El tipo de espacio es obligatorio',
            'space_type_id.exists' => 'El tipo de espacio no existe',
            'floor.required' => 'El piso es obligatorio',
            'floor.integer' => 'El piso debe ser un número entero',
            'capacity.required' => 'La capacidad es obligatoria',
            'capacity.min' => 'La capacidad debe ser al menos 1',
            'price_per_hour.required' => 'El precio por hora es obligatorio',
            'price_per_hour.numeric' => 'El precio por hora debe ser un número',
            'price_per_hour.min' => 'El precio por hora no puede ser negativo',
            'is_active.boolean' => 'El campo activo debe ser verdadero o falso',
        ];
    }
}