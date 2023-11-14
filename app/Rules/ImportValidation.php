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
    protected $fail_;

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
        $this->fail_ = $fail;
        $file = $this->data['file'];
        $startRow = $this->data['start_row'];
        $endRow = $this->data['end_row'];
        $userColumns = $value;
        $logType = 'import';

        // get excel pages
        $pages = \Excel::toArray(new \App\Imports\PostsImport, $file);

        // get first excel page
        $this->rows = $pages[0]??false;

        // basic rows validation
        if ($startRow > $endRow) {
            $this->fail("Start row must be less than end row");
            return;
        }
        if (count($this->rows) < $endRow) {
            $this->fail("Invalid end line number. Uploaded file contains only ".count($this->rows)." rows.");
            return;
        }

        if ($endRow-$startRow > 500) {
            $this->fail("Maximum lines to process - 500");
            return;
        }

        // get only specified rows
        $this->rows = array_slice($this->rows, $startRow-1, $endRow-$startRow+1);

        $totalRows = count($this->rows);
        $totalColumns = count($this->rows[0]);
        $hasError = $this->validateRequiredFields($userColumns, $totalColumns);

        if ($hasError) {
            return;
        }

        foreach ($this->rows as $rowIndex => $row) {
            foreach ($userColumns as $field => $columnIndex) {
                if ($columnIndex === null) {
                    continue;
                }

                $method = 'validate' . ucfirst($field);
                $this->$method($row[$columnIndex], $rowIndex+$startRow);
            }
        }
    }

    // validate user configuration of how columns will be imported
    private function validateRequiredFields($userColumns, $totalColumns)
    {
        $totalColumnsArray = range(0, $totalColumns);
        $hasError = false;

        if (!in_array($userColumns['title'], $totalColumnsArray)) {
            $hasError = true;
            $this->fail("A column for Title field is required");
        }

        if (!in_array($userColumns['description'], $totalColumnsArray)) {
            $hasError = true;
            $this->fail("A column for Description field is required");
        }

        if (!in_array($userColumns['category'], $totalColumnsArray)) {
            $hasError = true;
            $this->fail("A column for Category field is required");
        }

        return $hasError;
    }

    private function validateTitle($val, $i)
    {
        if (!$val) {
            $this->fail("Title can not be empty - row #$i");
            return;
        }
        if (strlen($val) > 255) {
            $this->fail("Title maximum length is 255 characters - row #$i");
        }
    }

    private function validateDescription($val, $i)
    {
        if (!$val) {
            $this->fail("Description can not be empty - row #$i");
            return;
        }

        if (strlen($val) > 9000) {
            $this->fail("Description maximum length is 9000 characters - row #$i");
        }
    }

    private function validateCategory($val, $i)
    {
        if (!$val) {
            $this->fail("Category can not be empty - row #$i");
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
            $this->fail("Category '$val' not recognized - row #$i");
        }
    }

    private function validateImages($val, $i)
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
                $this->fail("Image link '$link' not valid - row #$i");
                return;
            }
            // if (
            //     !str_ends_with($link, '.jpg') &&
            //     !str_ends_with($link, '.jpeg') &&
            //     !str_ends_with($link, '.png') &&
            //     !str_ends_with($link, '.webp')
            // ) {
            //     $this->fail("Image link '$link' must be an image link - row #$i");
            // }
        }
    }

    private function validateType($val, $i)
    {
        if ($val === null) {
            return;
        }

        if (!in_array(strtolower($val), Post::TYPES)) {
            $this->fail("Type '$val' not recognized - row #$i");
        }
    }

    private function validateCondition($val, $i)
    {
        if ($val === null) {
            return;
        }

        if (!in_array(strtolower($val), Post::CONDITIONS)) {
            $this->fail("Condition '$val' not recognized - row #$i");
        }
    }

    private function validateAmount($val, $i)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            $this->fail("Quantity maximum length is 70 characters - row #$i");
        }
    }

    private function validateManufacturer($val, $i)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            $this->fail("Manufacturer maximum length is 70 characters - row #$i");
        }
    }

    private function validateManufactureDate($val, $i)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            $this->fail("Manufacture Date maximum length is 70 characters - row #$i");
        }
    }

    private function validatePartNumber($val, $i)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            $this->fail("Part Number maximum length is 70 characters - row #$i");
        }
    }

    private function validateCost($val, $i)
    {
        if ($val === null) {
            return;
        }

        $currencies = array_values(currencies());
        $currency = $val[0];
        $val = substr($val, 1);

        if (!in_array($currency, $currencies)) {
            $this->fail("Cost currency '$currency' not recognized - row #$i");
        }

        if ($val != floatval($val)) {
            $this->fail("Cost '$val' not valid - row #$i");
        }
    }

    private function validateCountry($val, $i)
    {
        if ($val === null) {
            return;
        }

        $countries = array_keys(countries());
        if (!in_array(strtolower($val), $countries)) {
            $this->fail("Country '$val' not recognized - row #$i");
        }
    }

    private function fail($message)
    {
        activity('import')->event('error-validation')->log($message);
        $closure = $this->fail_;
        $closure($message);
    }
}
