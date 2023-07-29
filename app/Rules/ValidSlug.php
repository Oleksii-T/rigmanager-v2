<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Translation;

class ValidSlug implements Rule
{
    protected $class;
    protected $ignore;
    protected $requiredLocale;
    protected $error;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($class, $ignore=null, $requiredLocale='en')
    {
        $this->class = $class;
        $this->ignore = $ignore; //id of model to be ignored
        $this->requiredLocale = $requiredLocale;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // check for valid format
        $slugs = array_values($value);

        foreach ($slugs as $slug) {
            if (!preg_match('/^[a-z0-9-]*$/', $slug)) {
                $this->error = 'Invalid Slug. Only "a-z" and "-" allowed.';
                return false;
            }
        }

        // check for required locale
        if ($this->requiredLocale) {
            if (!isset($value[$this->requiredLocale]) || !$value[$this->requiredLocale]) {
                $this->error = "'$this->requiredLocale' slug required";
                return false;
            }
        }

        // check for uniqueness
        $ignore = $this->ignore;

        $existingSlugs = Translation::query()
            ->where('translatable_type', $this->class)
            ->where('field', 'slug')
            ->when($ignore, function ($query, $ignore) {
                $query->where('translatable_id', '!=', $ignore);
            })
            ->where('value', '!=', '')
            ->pluck('value')
            ->toArray();

        if ($slugs) {
            $this->error = 'Slugs must be unique';

            return !array_intersect($existingSlugs, $slugs);
        }


        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error;
    }
}
