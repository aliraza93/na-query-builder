<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Admin\User;
use App\Models\AD\AD_Computer;
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

$factory->define(AD_Computer::class, function (Faker $faker) {
    return [
        'object_guid' => $faker->uuid,
        'common_name' => $faker->name,
        'sam_account_name' => $faker->name,
        'obj_dist_name' => $faker->name,
        'last_logon' => Carbon::now(),
        'operating_system' => 'Windows',
        'operating_system_service_pack' => 'Service Pack 3',
        'operating_system_version' => '10.0',
        'when_created'  => Carbon::now(),
        'when_changed'  => Carbon::now()
    ];
});