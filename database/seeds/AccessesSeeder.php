<?php

use Illuminate\Database\Seeder;
use App\Access;
use Carbon\Carbon;

class AccessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $access = new Access();
        $access->name = 'Basic';
        $access->limit_create_event = 50;
        $access->created_at = Carbon::now()->timestamp;
        $access->save();

        $access2 = new Access();
        $access2->name = 'Medium';
        $access2->limit_create_event = 100;
        $access2->created_at = Carbon::now()->timestamp;
        $access2->save();

        $access3 = new Access();
        $access3->name = 'Premium';
        $access3->limit_create_event = 1000;
        $access3->created_at = Carbon::now()->timestamp;
        $access3->save();
    }
}
