<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetNewPassRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string|max:32|min:32|exists:password_reset_tokens,token',
            'password' => 'required|string|max:64|min:8|confirmed'
        ];
    }
}
