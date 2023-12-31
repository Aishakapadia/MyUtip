<?php

use Faker\Generator as Faker;

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

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'role_id'        => rand(2, 4),
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        //'date_of_birth'  => $faker->dateTimeThisCentury->format('Y-m-d'),
        'mobile'         => '',
        'sort'           => rand(0, 10),
        'active'         => 1,
        'remember_token' => str_random(10),
    ];
});
