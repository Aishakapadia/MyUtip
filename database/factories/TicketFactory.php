<?php

use Faker\Generator as Faker;

$factory->define(App\Ticket::class, function (Faker $faker) {
    $title = $faker->title . ' ' . rand(1, 999999);
    return [
        'title'       => $title,
        'slug'        => str_slug($title),
        'description' => $faker->text,
        'type'        => $faker->name,
        'created_at'  => $faker->dateTime(),
        'updated_at'  => $faker->dateTime(),
    ];
});
