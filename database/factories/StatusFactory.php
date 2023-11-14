<?php

use Faker\Generator as Faker;

$factory->define(App\Status::class, function (Faker $faker) {
    $title = $faker->title . ' ' . rand(1, 999999);
    return [
        'role_id'     => rand(1, 3),
        'title'       => $title,
        'slug'        => str_slug($title),
        'description' => $faker->text,
        'active'      => 1,
        'created_at'  => date('Y-m-d H:i:s'),
        'updated_at'  => date('Y-m-d H:i:s'),
    ];
});
