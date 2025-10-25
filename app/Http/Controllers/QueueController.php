<?php

namespace App\Http\Controllers;

use App\Events\MakeQueue;
use App\Models\Queue;
use App\Models\Room;
use App\Models\Type;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    function index(Request $request){
        $queues = Queue::all();
        if($request->has('type')){
            $queues = Queue::where('type_id', $request->type)->get();
        }
        $types = Type::all();
        $rooms = Room::all();
        return view('queues.index', compact('queues', 'types', 'rooms'));
    }

    function create(){
        $rooms = Room::with('active_queue')->get();
        return view('queues.create', compact('rooms'));
    }
    
    function edit(Queue $queue){
        $rooms = Room::with('active_queue')->get();
        $queue->load('room', 'type');    
        return view('queues.edit', compact('rooms', 'queue'));

    }

    function store(){
        $queue = Queue::create(['room_id' => request()->room_id, 'name' => request()->name, 'type_id' => request()->type_id]);
        MakeQueue::dispatch($queue);
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.', 'data' => $queue]);
    }
    
    function update(Queue $queue, Request $request){
        $queue->update($request->except('_method'));
        MakeQueue::dispatch($queue);
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.', 'data' => $queue]);

    }
    
    function getAll(Request $request){
        $queues = Queue::where('status', 1)->get();
        if($request->has('type'))
            $queues = Queue::where('type_id', $request->type)->where('status', 1)->get();
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.', 'data' => $queues]);
    }
    
    function destroy(Queue $queue){
        $queue->update(['status' => 0]);
        MakeQueue::dispatch($queue);
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.']);

    }
}
