<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\channel;
use Faker\Generator as Faker;

$factory->define(channel::class, function (Faker $faker) {
    $name = $faker->sentence(4);
    return [
        'name' => $name,
        'slug' => \Illuminate\Support\Str::slug($name),
    ];
});
