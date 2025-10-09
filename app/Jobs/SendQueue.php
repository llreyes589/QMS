<?php

namespace App\Jobs;

use App\Events\MakeQueue;
use App\Models\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(public Queue $queue)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MakeQueue::dispatch([
            'id' => $this->queue->id,
            'room_id' => $this->queue->room_id,
            'name' => $this->queue->name,
            'time' => $this->queue->time,
        ]);
    }
}
