<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 20),
        'title' => 'title' . $faker->numberBetween($min = 1, $max = 1000),
        'content' => 'content' . $faker->numberBetween($min = 1, $max = 1000),
        'status' => $faker->numberBetween($min = 1, $max = 3),
        'private' => $faker->numberBetween($min = 1, $max = 2),
        'due_date' => $faker->datetimeBetween($startDate = 'now', $endDate = '+1 years')->format('Y-m-d'),
    ];
});
