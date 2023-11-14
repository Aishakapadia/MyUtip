<?php

use Faker\Generator as Faker;

$factory->define(App\Site::class, function (Faker $faker) {
    $title = $faker->title . ' ' . rand(1, 999999);
    return [
        'site_type_id'  => 1,
        'title'         => $title,
//        'slug'          => str_slug($title),
        'description'   => $faker->text,
        'material_type' => $faker->name,
        //'site_type'     => $faker->name,
        'created_at'    => date('Y-m-d H:i:s'),
        'updated_at'    => date('Y-m-d H:i:s'),
    ];
});
