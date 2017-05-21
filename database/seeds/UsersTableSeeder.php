<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        // Get admin role
        $adminRole = DB::table('roles')
            ->select('id')
            ->where('name', 'admin')
            ->first()
            ->id;

        // Reset table
        DB::table('users')->delete();

        // Create admin user
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@hotelquickly.com',
            'password' => bcrypt('admin'),
            'role_id' => $adminRole,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
