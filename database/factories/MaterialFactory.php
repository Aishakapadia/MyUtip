<?php

use Faker\Generator as Faker;

$factory->define(App\Material::class, function (Faker $faker) {
    $title = $faker->title . ' ' . rand(1, 999999);
    return [
        'sap_code'    => $faker->numberBetween(1, 100000),
        'title'       => $title,
        //'slug'        => str_slug($title),
        'description' => $faker->text,
        'type'        => 'RM',
        'active'      => 1,
        'created_at'  => date('Y-m-d H:i:s'),
        'updated_at'  => date('Y-m-d H:i:s'),
    ];
});
