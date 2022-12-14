<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $adminRole = Role::where('name', 'admin')->value('id');
        $userRole = Role::where('name', 'user')->value('id');
        $users = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'country' => 'ua',
                'password' => Hash::make('admin@mail.com'),
                'email' => 'admin@mail.com',
                'email_verified_at' => now(),
                'last_active_at' => now()
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'country' => 'ch',
                'password' => Hash::make('user@mail.com'),
                'email' => 'user@mail.com',
                'email_verified_at' => now(),
                'last_active_at' => now()
            ],
            [
                'name' => 'User 1',
                'slug' => 'user-1',
                'country' => 'ru',
                'password' => Hash::make('user1@mail.com'),
                'email' => 'user1@mail.com',
                'last_active_at' => now()
            ],
        ];

        foreach ($users as $user) {
            $model = User::updateOrCreate(
                [
                    'email' => $user['email']
                ],
                $user
            );

            if ($user['email'] == 'admin@mail.com') {
                $model->roles()->sync([$adminRole]);
            } else {
                $model->roles()->sync([$userRole]);
            }
        }
    }
}
