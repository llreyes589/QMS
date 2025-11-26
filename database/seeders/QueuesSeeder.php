<?php

namespace Database\Seeders;

use App\Events\MakeQueue;
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
        foreach (range(1, 8) as $index) {
            $que = Queue::create([
                'room_id' => rand(1, 28),
                'name' => $faker->name,
                'status' => 1,
                'room_id' => rand(1, 28),
                "type_id" => rand(1,2)
            ]);
            MakeQueue::dispatch($que);
        }
    }
}
