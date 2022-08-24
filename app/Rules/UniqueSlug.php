<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Translation;

class UniqueSlug implements Rule
{
    protected $class;
    protected $ignore;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($class, $ignore=null)
    {
        $this->class = $class;
        $this->ignore = $ignore;
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
        $ignore = $this->ignore;

        $slugs = Translation::query()
            ->where('translatable_type', $this->class)
            ->where('field', 'slug')
            ->when($ignore, function ($query, $ignore) {
                $query->where('translatable_id', '!=', $ignore);
            })
            ->pluck('value')
            ->toArray();

        if (!$slugs) {
            return true;
        }

        $value = array_values($value);

        return array_intersect($slugs, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Slug must be unique';
    }
}
