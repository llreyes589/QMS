<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = ['room_id', 'name', 'status', 'type_id'];

    function room(){
        return $this->belongsTo(Room::class);
    }

    function type(){
        return $this->belongsTo(Type::class);
    }
}
