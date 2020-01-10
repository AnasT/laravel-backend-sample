<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Field;
use App\FieldType;
use Faker\Generator as Faker;

$factory->define(Field::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type' => $faker->randomElement(FieldType::ALLOWED_TYPES),
    ];
});
