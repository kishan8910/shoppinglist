<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

/**
 * factory used for creating an item
 */
$factory->define(Item::class, function (Faker $faker) {

    // returns the item object
    return [
        'name' => $faker->word,
        'price' => $faker->randomElement([1,50]),
        'bought' => $faker->randomElement([0,1])
    ];
});
