<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->defineAs(App\User::class, 'admin', function (Faker $faker) {
    return [
        'username' => 'admin',
        'password' => Hash::make('admin'),
        'account_type' => 1
    ];
});

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->userName,
//        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'password' => Hash::make('secret'),
        'account_type' => 2
    ];
});
