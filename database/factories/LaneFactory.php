<?php

use Faker\Generator as Faker;

$factory->define(App\Lane::class, function (Faker $faker) {
    $title = $faker->title . ' ' . rand(1, 999999);
    return [
        'title'       => $title,
        //'slug'        => str_slug($title),
        'description' => $faker->text,
        'created_at'  => date('Y-m-d H:i:s'),
        'updated_at'  => date('Y-m-d H:i:s'),
    ];
});
