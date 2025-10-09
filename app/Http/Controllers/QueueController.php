<?php

namespace App\Http\Controllers;

use App\Events\MakeQueue;
use App\Models\Queue;
use App\Models\Room;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    function index(){
        $queues = Queue::all();
        return view('queues.index', compact('queues'));
    }

    function create(){
        $rooms = Room::with('active_queue')->get();
        return view('queues.create', compact('rooms'));
    }

    function store(){
        $queue = Queue::create(['room_id' => request()->room_id, 'name' => request()->name]);
        MakeQueue::dispatch($queue);
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.', 'data' => $queue]);
    }
    
    function getAll(){
        $queues = Queue::where('status', 1)->get();
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.', 'data' => $queues]);
    }
    
    function destroy(Queue $queue){
        $queue->update(['status' => 0]);
        MakeQueue::dispatch($queue);
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.']);

    }
}
