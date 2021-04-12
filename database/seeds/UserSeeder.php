<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')
        ->insert([
            [
                'name' => 'Guest',
                'read' => true,
                'insert' => false,
                'update' => false,
                'delete' => false,
                'admin' => false,
                'active' => true,
                'when_created' => Carbon::now()
            ],
            [
                'name' => 'User',
                'read' => true,
                'insert' => true,
                'update' => true,
                'delete' => true,
                'admin' => false,
                'active' => true,
                'when_created' => Carbon::now()
            ],
            [
                'name' => 'Admin',
                'read' => true,
                'insert' => true,
                'update' => true,
                'delete' => true,
                'admin' => true,
                'active' => true,
                'when_created' => Carbon::now()
            ]
        ]);

        DB::table('login_user')
        ->insert([
            [
                'first_name'    => 'Admin',
                'last_name'     => 'User',
                'user_name'     => 'admin',
                'email'         => 'admin@admin.com',
                'password'      => bcrypt('1234'),
                'user_type_id'  => 3,
                'when_created'  => Carbon::now()
            ]
        ]);

        DB::table('permissions')
        ->insert([
            [
                'name' => 'CRM',
                'code' => 'CRM',
                'path' => 'crm',
                'when_created' => Carbon::now()
            ],
            [
                'name' => 'administracja',
                'code' => 'ADMIN',
                'path' => 'admin',
                'when_created' => Carbon::now()
            ]
        ]);

        DB::table('user_permissions')
        ->insert([
            ['user_id' => 3,'permission_id' => 1],
            ['user_id' => 3,'permission_id' => 2]
        ]);

        DB::table('company_types')
        ->insert([
            ['name' => 'Media','code' => 'MED'],
            ['name' => 'Firmy IT','code' => 'FIT'],
            ['name' => 'Sklepy','code' => 'SKL'],
            ['name' => 'Organizacje','code' => 'ORG'],
        ]);
    }
}
