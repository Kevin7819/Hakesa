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
            'customer_name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'customer_email.required' => 'El correo electrónico es obligatorio.',
            'customer_email.email' => 'Ingresa un correo electrónico válido.',
            'customer_phone.required' => 'El teléfono es obligatorio.',
            'customer_phone.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'notes.max' => 'Las notas no pueden tener más de 1000 caracteres.',
            'customizations.*.max' => 'La personalización no puede tener más de 500 caracteres.',
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
