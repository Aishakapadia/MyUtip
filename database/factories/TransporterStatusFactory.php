<?php

use Faker\Generator as Faker;

$factory->define(App\TransporterStatus::class, function (Faker $faker) {
    $title = $faker->title . ' ' . rand(1, 999999);
    return [
        'title'       => $title,
        'slug'        => str_slug($title),
        'description' => $faker->text,
        //'color_code'  => $faker->hexColor,
        'active'      => 1,
        'created_at'  => date('Y-m-d H:i:s'),
        'updated_at'  => date('Y-m-d H:i:s'),
    ];
});
