<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Role::class, function (Faker\Generator $faker) {
    return [
        'title'       => $faker->name,
        'slug'        => str_slug($faker->name),
        'description' => $faker->text(),
        'active'      => 1,
        'created_at'  => $faker->dateTime(),
        'updated_at'  => $faker->dateTime(),
    ];
});
