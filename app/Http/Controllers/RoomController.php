<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    function getAll(){
        $rooms = Room::with('active_queue')->get();
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.', 'data' => $rooms]);
    }
}
