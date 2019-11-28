<?php

use Faker\Generator as Faker;

$factory->define(App\UserProfile::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'country' => $faker->country,
        'address' => $faker->address,
    ];
});
