<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Stevebauman\Location\Facades\Location;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'agreement' => ['required', 'accepted'],
            'phone' => ['nullable', 'string', 'max:40']
        ])->validate();

        return User::create([
            'country' => strtolower(Location::get()->countryCode),
            'name' => $input['name'],
            'slug' => makeSlug($input['name'], User::pluck('slug')->toArray()),
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => Hash::make($input['password']),
            'last_active_at' => now(),
        ]);
    }
}
