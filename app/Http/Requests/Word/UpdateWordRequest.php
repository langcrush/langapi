<?php

namespace App\Http\Requests\Word;

use App\Rules\CategoryExistsFilledRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWordRequest extends FormRequest
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
            'name' => 'string|max:50',
            'translation' => 'string|max:50',
            'category_id' => ['integer', new CategoryExistsFilledRule()]
        ];
    }
}
