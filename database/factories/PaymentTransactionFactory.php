<?php

use Faker\Generator as Faker;
use Lari\Payments\PaymentTransaction;

$factory->define(PaymentTransaction::class, function (Faker $faker) {
    return [
        'provider_id' => str_random(10),
        'amount'      => rand(100, 1000) * 100,
        'status'      => $faker->word,
        'ip'          => $faker->ipv4,
    ];
});
