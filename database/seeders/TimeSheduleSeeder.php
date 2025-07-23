<?php

namespace Database\Seeders;

use App\Models\TimeShedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimeShedule::updateOrCreate(['day' => 'Monday'],['start_time' => '07:00','end_time' => '20:30']);
        TimeShedule::updateOrCreate(['day' => 'Tuesday'],['start_time' => '07:00','end_time' => '20:30']);
        TimeShedule::updateOrCreate(['day' => 'Wednesday'],['start_time' => '07:00','end_time' => '20:30']);
        TimeShedule::updateOrCreate(['day' => 'Thursday'],['start_time' => '07:00','end_time' => '20:30']);
        TimeShedule::updateOrCreate(['day' => 'Friday'],['start_time' => '07:00','end_time' => '20:30']);
        TimeShedule::updateOrCreate(['day' => 'Saturday'],['start_time' => '07:00','end_time' => '20:30']);
        TimeShedule::updateOrCreate(['day' => 'Sunday'],['start_time' => '07:00','end_time' => '20:30']);
    }
}
