<?php

use Faker\Generator as Faker;
use Lari\Payments\PaymentGateway;

$factory->define(PaymentGateway::class, function (Faker $faker) {
    return [
        'name'        => $faker->words(2, true),
        'title'       => $faker->words(4, true),
        'key'         => $faker->word,
        'description' => $faker->sentence,
        'is_active'   => false,
        'order'   => 0,
    ];
});
