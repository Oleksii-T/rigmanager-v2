<?php

namespace App\Rules;

use Closure;
use App\Models\Post;
use App\Models\Category;
use App\Models\Translation;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ImportValidation implements DataAwareRule, ValidationRule
{
    protected $data = [];
    protected $rows;

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $file = $this->data['file'];
        $startRow = $this->data['start_row'];
        $endRow = $this->data['end_row'];
        $userColumns = $value;

        // get excel pages
        $pages = \Excel::toArray(new \App\Imports\PostsImport, $file);

        // get first excel page
        $this->rows = $pages[0]??false;

        // basic rows validation
        if ($startRow > $endRow) {
            $fail("Start row must be less than end row");
            return;
        }
        if (count($this->rows) < $endRow) {
            $fail("Invalid end line number. Uploaded file contains only ".count($this->rows)." rows.");
            return;
        }

        if ($endRow-$startRow > 500) {
            $fail("Maximum lines to process - 500");
            return;
        }

        // get only specified rows
        $this->rows = array_slice($this->rows, $startRow-1, $endRow-$startRow+1);

        $totalRows = count($this->rows);
        $totalColumns = count($this->rows[0]);
        $hasError = $this->validateRequiredFields($userColumns, $totalColumns, $fail);

        if ($hasError) {
            return;
        }

        foreach ($this->rows as $rowIndex => $row) {
            foreach ($userColumns as $field => $columnIndex) {
                if ($columnIndex === null) {
                    continue;
                }

                $method = 'validate' . ucfirst($field);
                $this->$method($row[$columnIndex], $fail, $rowIndex+$startRow);
            }
        }
    }

    private function validateRequiredFields($userColumns, $totalColumns, $fail)
    {
        $totalColumnsArray = range(0, $totalColumns);
        $hasError = false;

        if (!in_array($userColumns['title'], $totalColumnsArray)) {
            $hasError = true;
            $fail("A column for Title field is required");
        }

        if (!in_array($userColumns['description'], $totalColumnsArray)) {
            $hasError = true;
            $fail("A column for Description field is required");
        }

        if (!in_array($userColumns['category'], $totalColumnsArray)) {
            $hasError = true;
            $fail("A column for Category field is required");
        }

        return $hasError;
    }

    private function validateTitle($val, $fail, $i)
    {
        if (!$val) {
            $fail("Title can not be empty - row #$i");
            return;
        }
        if (strlen($val) > 255) {
            $fail("Title maximum length is 255 characters - row #$i");
        }
    }

    private function validateDescription($val, $fail, $i)
    {
        if (!$val) {
            $fail("Description can not be empty - row #$i");
            return;
        }

        if (strlen($val) > 9000) {
            $fail("Description maximum length is 9000 characters - row #$i");
        }
    }

    private function validateCategory($val, $fail, $i)
    {
        if (!$val) {
            $fail("Category can not be empty - row #$i");
            return;
        }

        $slugs = cache()->remember('posts-import.categories-slugs', 60, function() {
            return Translation::where('translatable_type', Category::class)->where('field', 'slug')->where('locale', 'en')->pluck('value');
        });

        if ($slugs->contains($val)) {
            return true;
        }

        $slugs = cache()->remember('posts-import.categories-names', 60, function() {
            return Translation::where('translatable_type', Category::class)->where('field', 'name')->where('locale', 'en')->pluck('value');
        });

        if (!$slugs->contains($val)) {
            $fail("Category '$val' not recognized - row #$i");
        }
    }

    private function validateImages($val, $fail, $i)
    {
        if ($val === null) {
            return;
        }

        $links = explode(' ', $val);

        foreach ($links as $link) {
            if (!$link) {
                continue;
            }

            $pattern = '/\s*/m';
            $replace = '';
            $link = preg_replace($pattern, $replace, $link);
            $link = trim($link);

            if (!$link) {
                continue;
            }

            if (filter_var($link, FILTER_VALIDATE_URL) === FALSE) {
                $fail("Image link '$link' not valid - row #$i");
                return;
            }
            // if (
            //     !str_ends_with($link, '.jpg') &&
            //     !str_ends_with($link, '.jpeg') &&
            //     !str_ends_with($link, '.png') &&
            //     !str_ends_with($link, '.webp')
            // ) {
            //     $fail("Image link '$link' must be an image link - row #$i");
            // }
        }
    }

    private function validateType($val, $fail, $i)
    {
        if ($val === null) {
            return;
        }

        if (!in_array(strtolower($val), Post::TYPES)) {
            $fail("Type '$val' not recognized - row #$i");
        }
    }

    private function validateCondition($val, $fail, $i)
    {
        if ($val === null) {
            return;
        }

        if (!in_array(strtolower($val), Post::CONDITIONS)) {
            $fail("Condition '$val' not recognized - row #$i");
        }
    }

    private function validateAmount($val, $fail, $i)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            $fail("Quantity maximum length is 70 characters - row #$i");
        }
    }

    private function validateManufacturer($val, $fail, $i)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            $fail("Manufacturer maximum length is 70 characters - row #$i");
        }
    }

    private function validateManufactureDate($val, $fail, $i)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            $fail("Manufacture Date maximum length is 70 characters - row #$i");
        }
    }

    private function validatePartNumber($val, $fail, $i)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            $fail("Part Number maximum length is 70 characters - row #$i");
        }
    }

    private function validateCost($val, $fail, $i)
    {
        if ($val === null) {
            return;
        }

        $currencies = array_values(currencies());
        $currency = $val[0];
        $val = substr($val, 1);

        if (!in_array($currency, $currencies)) {
            $fail("Cost currency '$currency' not recognized - row #$i");
        }

        if ($val != floatval($val)) {
            $fail("Cost '$val' not valid - row #$i");
        }
    }

    private function validateCountry($val, $fail, $i)
    {
        if ($val === null) {
            return;
        }

        $countries = array_keys(countries());
        if (!in_array(strtolower($val), $countries)) {
            $fail("Country '$val' not recognized - row #$i");
        }
    }
}
