<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Admin\User;
use App\Models\AD\AD_Groups;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Carbon\Carbon;

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

$factory->define(AD_Groups::class, function (Faker $faker) {
    return [
        'object_guid' => $faker->uuid,
        'common_name' => $faker->name,
        'obj_dist_name' => $faker->name,
        'when_created'  => Carbon::now(),
        'when_changed'  => Carbon::now()
    ];
});
