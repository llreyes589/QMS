<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    function index(){
        $queues = Queue::all();
        return view('queues.index', compact('queues'));
    }
}
