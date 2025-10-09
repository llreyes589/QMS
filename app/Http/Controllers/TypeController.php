<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    function getAll(){
        $types = Type::all();
        return response()->json(['statusCode'=> 200, 'message' => 'Successfully processed.', 'data' => $types]);
    }
}
