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
        $rooms = Room::all();
        return view('queues.create', compact('rooms'));
    }

    function store(){
        $queue = Queue::create(['room_id' => request()->room_id, 'name' => request()->name]);
        MakeQueue::dispatch($queue);
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.', 'data' => $queue]);
    }
    
    function getAll(){
        $queues = Queue::all();
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.', 'data' => $queues]);
    }
}
