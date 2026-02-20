<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRules;

class RegistroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                PasswordRules::min(8)->letters()->symbols()->numbers()
            ]
        ];
    }

    public function messages()
    {
        return [
            'name' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'No es un email válido',
            'email.unique' => 'El Email ya existe',
            'password.required' => 'El password es obligatorio',
            'password.confirmed' => 'Los Password no coinciden',
            'password.min' => 'El password tiene que tener minimo 8 caracteres',
            'password.letters' => 'El password debe contener al menos una letra',
            'password.symbols' => 'El password debe contener al menos un simbolo',
            'password.numbers' => 'El password debe contener al menos un numero'
        ];
    }
}
