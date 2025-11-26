<?php

namespace Database\Seeders;

use App\Events\InactiveQueue;
use App\Models\Queue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetInactiveQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Queue::where('status', 1)->chunk(10,function($queues){
            foreach ($queues as $queue) {
                $queue->update(['status' => 0]);
                InactiveQueue::dispatch($queue);
            }
        });
    }
}
