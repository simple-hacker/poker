<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Attributes\Limit;
use App\Attributes\Stake;
use App\Attributes\Variant;
use Illuminate\Support\Str;
use App\Attributes\TableSize;
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

$factory->define(User::class, function (Faker $faker) {

    $locales = ['en-GB', 'en-US', 'en-IE', 'fr-FR', 'de-DE', 'pl-PL', 'fr-CA', 'en-CA', 'en-AU'];
    $currencies = ['GBP', 'USD', 'EUR', 'PLN', 'CAD', 'AUD'];

    
    return [
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'setup_complete' => false,
        'locale' => $faker->randomElement($locales),
        'currency' => $faker->randomElement($currencies),
        'default_stake_id' => Stake::inRandomOrder()->first(),
        'default_limit_id' => Limit::inRandomOrder()->first(),
        'default_variant_id' => Variant::inRandomOrder()->first(),
        'default_table_size_id' => TableSize::inRandomOrder()->first(),
        'default_location' => 'Casino MK'
    ];
});
