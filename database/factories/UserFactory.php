<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'address' => $faker->address,
        'city' => $faker->city,
        'phone' => $faker->phoneNumber,
    ];
});

$factory->define(\App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(2),
    ];
});

$factory->define(\App\Author::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'bio' => $faker->paragraph(2),
        'image' => $faker->randomElement([
            'authors/1.jpg', 'authors/2.jpg', 'authors/3.jpg', 'authors/4.jpg',
            'authors/5.jpg', 'authors/6.jpg', 'authors/7.jpg', 'authors/8.jpg',
            'authors/9.jpg', 'authors/10.jpg', 'authors/11.jpg', 'authors/12.jpg', 'authors/no_author_image.jpg'
            ]),
    ];
});

$factory->define(\App\Inventory::class, function (Faker $faker) {
    return [
        'quantity' => $faker->numberBetween(5, 20),
    ];
});

$factory->define(\App\Book::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(2),
        'author_id' => \App\Author::all()->random()->id,
        'inventory_id' => \App\Inventory::all()->random()->id,
        'image' => $faker->randomElement([
            'books/1.jpg', 'books/2.jpg', 'books/3.jpg', 'books/4.jpg',
            'books/5.jpg', 'books/6.jpg', 'books/7.jpg', 'books/8.jpg', 'books/no_book_image.jpg',
        ]),
    ];
});

$factory->define(\App\Rental::class, function (Faker $faker) {
    $status = $faker->randomElement([\App\Rental::RENT_STATUS, \App\Rental::COMPLETE_STATUS]);
    $date = date("Y-m-d H:i:s");
    $end_date = ($status === \App\Rental::COMPLETE_STATUS) ? $date : null;
    return [
        'book_id' => \App\Book::all()->random()->id,
        'user_id' => \App\User::all()->random()->id,
        'status' => $status,
        'end_date' => $end_date,
    ];
});
