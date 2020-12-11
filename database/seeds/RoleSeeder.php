<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->role = 'Admin';
        $role->status = 1;
        $role->created_at = Carbon::now()->timestamp;
        $role->save();

        $role2 = new Role();
        $role2->role = 'User';
        $role2->status = 2;
        $role2->created_at = Carbon::now()->timestamp;
        $role2->save();
    }
}
