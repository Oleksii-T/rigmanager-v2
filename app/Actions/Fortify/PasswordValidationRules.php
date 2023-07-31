<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules($r=true)
    {
        return [$r ? 'required' : 'nullable', 'string', 'min:4', 'confirmed']; //new Password
    }
}
