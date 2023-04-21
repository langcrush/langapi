<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CategoryExistsFilledRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $category = DB::table('categories')->find($value);
        if(empty($category)){
            $fail('The :attribute must exist in the DB.');
            return;
        }
        $words = DB::table('words')->where('category_id', $value)->count();
        if($words >= 50){
            $fail('This category (' . $category->name . ') already has enough words.');
            return;
        }
    }
}
