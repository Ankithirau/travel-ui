<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $num=1;
        for ($i=0; $i <84 ; $i++) { 
            DB::table('attendee_info')->insert([
                'attendee_name' => Str::random(10),
                'attendee_number' => 9977678909,
                'booking_id' => $num++,
                'created_by' => 1,
            ]);
        }
    }
}
