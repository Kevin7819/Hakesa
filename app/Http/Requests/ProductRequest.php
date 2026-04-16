<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($productId)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'service_type' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,avif', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.unique' => 'Ya existe un producto con ese nombre.',
            'price.required' => 'El precio es obligatorio.',
            'price.min' => 'El precio no puede ser negativo.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no puede pesar más de 5MB.',
        ];
    }
}
