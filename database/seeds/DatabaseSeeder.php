<?php

use App\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();

        Role::insert([
            ['name' => 'doctor', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'admin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'patient', 'created_at' => $now, 'updated_at' => $now],
        ]);
        // $this->call(UsersTableSeeder::class);
    }
}
