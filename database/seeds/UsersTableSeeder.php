<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     * @throws Exception
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

        $adminUser = [
            'name' => 'Administrator',
            'email' => env('APP_ADMIN'),
            'password' => bcrypt('admin'),
            'role_id' => $adminRole,
            'created_at' => $now,
            'updated_at' => $now
        ];

        $validator = Validator::make($adminUser,
            ['email' => 'required|email|max:150'],
            [
                'email.required' => 'Please enter email address for admin user',
                'email.email' => 'Please enter valid email address for admin user'
            ]
        );

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first('email'));
        }

        // Create admin user
        DB::table('users')->insert($adminUser);
    }
}
