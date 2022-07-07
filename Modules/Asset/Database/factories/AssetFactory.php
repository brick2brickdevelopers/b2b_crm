<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;


$types = [
    'Laptop',
    'Desktop',
    'Mobile',
    'Printer',
    'Scanner',
    'Two-Wheeler',
    'Car',
    'Other'
];

$factory->define(
    \Modules\Asset\Entities\Asset::class,
    function(Faker $faker) use($types) {
        return [
            'name' => ucwords(implode(' ', $faker->words(2))),
            'company_id' =>1,
            'serial_number'  => ($faker->numberBetween(0, 5) == 0) ? null : $faker->swiftBicNumber,
            'description' => ($faker->numberBetween(0, 2) == 0) ? null : $faker->text(100),
            'status' => $faker->randomElement(['available', 'non-functional','lent']),
            'created_at'   => $faker->dateTimeThisYear('2020-02-02'),
        ];
    }
);

$factory->define(
    \Modules\Asset\Entities\AssetHistory::class,
    function(Faker  $faker) use($types) {
        return [
            'date_given'  => $given = $faker->dateTimeThisYear('2020-02-02'),
            'return_date' => (clone $given)->add(DateInterval::createFromDateString($faker->numberBetween(1, 90) . ' days')),
            'date_of_return' => (clone $given)->add(DateInterval::createFromDateString($faker->numberBetween(1, 120) . ' days')),
            'created_at'   => $faker->dateTimeThisYear('2020-02-02'),
        ];
    }
);

