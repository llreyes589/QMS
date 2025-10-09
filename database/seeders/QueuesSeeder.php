<?php

namespace Database\Seeders;

use App\Models\Queue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class QueuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            Queue::create([
                'room_id' => rand(1, 28),
                'name' => $faker->name,
                'status' => 1,
            ]);
        }
    }
}
