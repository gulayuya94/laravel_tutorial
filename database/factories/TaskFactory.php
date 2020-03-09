<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'user_id' => 6,
        'title' => $faker->word,
        'content' => $faker->sentence,
        'status' => $faker->numberBetween($min = 1, $max = 3),
        'due_date' => $faker->date($format='Y-m-d',$min='now'),
    ];
});
