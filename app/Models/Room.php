<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $fillable = ['room_code', 'description'];

    public function active_queue(){
        return $this->hasOne(Queue::class)->where('status', 1);
    }
}
