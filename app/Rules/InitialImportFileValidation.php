<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InitialImportFileValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $pages = \Excel::toArray(new \App\Imports\PostsImport, $value);
            $rows = $pages[0]??false; // get first excel page
            $rowsLeast = 1;
            $colunmsLeast = 3;

            if (!$rows) {
                $fail('File must have at least one page');
            }

            if (count($rows) < $rowsLeast) {
                $fail('File must have at least one row');
            }

            if (count($rows[0]) < $colunmsLeast) {
                $fail("File must have at least $colunmsLeast columns");
            }
        } catch (\Throwable $th) {
            \Log::error('ERROR on initial import file validation: ' . $th->getMessage());
            $fail("Can not read file");
        }
    }
}
