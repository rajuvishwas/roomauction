<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    protected $table = 'roles';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $defaultRoles = collect(['admin', 'partner']);

        // Get the roles which are not inserted into table
        $roles = DB::table($this->table)->pluck('name');
        $roles = $defaultRoles->diff($roles);

        if ($roles->count() != 0) {

            $data = [];
            foreach ($roles as $role) {
                $data[] = ['name' => $role, 'created_at' => $now, 'updated_at' => $now];
            }

            DB::table('roles')->insert($data);
        }

    }
}
