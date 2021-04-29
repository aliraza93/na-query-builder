<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Admin\User;
use App\Models\AD\Ad_user;
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

$factory->define(Ad_user::class, function (Faker $faker) {
    // dd($faker->uuid);
    $emails = [];
    array_push($emails, $faker->unique()->safeEmail);
    return [
        'object_guid' => $faker->uuid,
        'common_name' => $faker->name,
        'surname' => $faker->name,
        'given_name' => $faker->name,
        'sam_account_name' => $faker->name,
        'physical_delivery_office_name' => $faker->name,
        'telephone_number' => '',
        // 'email_addresses' => $emails,
        'department' => '',
        'obj_dist_name' => $faker->name,
        'last_logon' => Carbon::now(),
        'logon_count' => 1,
        'user_principal_name' => '',
        'is_enabled' => true,
        'when_created'  => Carbon::now(),
        'when_changed'  => Carbon::now()
    ];
});
