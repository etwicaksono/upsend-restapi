<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->username = 'imammufiid';
        $user->firstname  = 'Imam Mufiid';
        $user->lastname  = 'Mufiid';
        $user->email  = 'imammufiid@gmail.com';
        $user->role_id  = 1;
        $user->access_id  = 1;
        $user->password  = bcrypt("password");
        $user->created_at = Carbon::now()->timestamp;
        $user->save();
    }
}
