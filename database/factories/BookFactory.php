<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'book_name' => $faker->word,
        'book_description' => $faker->paragraph(),
        'book_author' => $faker->name,
        'user_id' => App\User::all()->random()->id
    ];
});
