<?php

use Illuminate\Database\Seeder;
// use App\Models\AD\Ad_user;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // $computers = factory(App\Models\AD\AD_Computer::class, 50)->create();
        // $users = Ad_user::factory()->count(30)->make();
        // $groups = factory(App\Models\AD\AD_Groups::class, 50)->create();
        // $containers = factory(App\Models\AD\AD_Container::class, 50)->create();
        // $ous = factory(App\Models\AD\AD_Ous::class, 50)->create();
        $ip_address = factory(App\Models\AD\IP_address::class, 50)->create();
    }
}
