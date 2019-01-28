<?php

use Faker\Generator as Faker;

$factory->define(App\BookList::class, function (Faker $faker) {
    return [
        'book_title' => $faker->slug,
        'book_author' => $faker->name
    ];
});
