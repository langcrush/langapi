<?php

namespace App\Http\Requests;

use App\Rules\CategoryExistsFilledRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWordRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'translation' => 'required|string|max:50',
            'category_id' => ['required', 'integer', new CategoryExistsFilledRule()]
        ];
    }
}
