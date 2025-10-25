<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeIntervalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'title' => 'time_interval',
            'value' => 30000,
            'description' => "[REFRESH PAGE REQUIRED] Time interval to switch between Walk-in and Appoinment type. Note: the value must be in millisecond."
        ]);
    }
}
