<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'El nombre es obligatorio.',
            'customer_name.max' => 'El nombre no puede tener m\u00e1s de 255 caracteres.',
            'customer_email.required' => 'El correo electr\u00f3nico es obligatorio.',
            'customer_email.email' => 'Ingresa un correo electr\u00f3nico v\u00e1lido.',
            'customer_phone.required' => 'El tel\u00e9fono es obligatorio.',
            'customer_phone.max' => 'El tel\u00e9fono no puede tener m\u00e1s de 20 caracteres.',
            'notes.max' => 'Las notas no pueden tener m\u00e1s de 1000 caracteres.',
            'customizations.*.max' => 'La personalizaci\u00f3n no puede tener m\u00e1s de 500 caracteres.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'customizations' => ['nullable', 'array'],
            'customizations.*' => ['nullable', 'string', 'max:500'],
        ];
    }
}
