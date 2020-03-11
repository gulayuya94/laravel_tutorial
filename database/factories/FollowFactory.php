<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Follow::class, function (Faker $faker) {
    return [
        'follower_id' => $faker->numberBetween($min = 1, $max = 20),
        'followee_id' => $faker->numberBetween($min = 1, $max = 20),
        'accept_status' => 2,
    ];
});
