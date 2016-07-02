<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(4),
        'content' => $faker->paragraph(4),
        'user_id' => mt_rand(1, 10)
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph(1),
        'post_id' => mt_rand(1, 50),
        'user_id' => mt_rand(1, 10)
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {

    $hasher = app()->make('hash');
    
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => $hasher->make("secret"),
        'is_admin' => mt_rand(0, 1)
    ];
});