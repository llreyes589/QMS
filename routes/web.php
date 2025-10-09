<?php

use App\Events\MakeQueue;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\RoomController;
use App\Models\Queue;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('queues', QueueController::class);

Route::name('queues')->group(function(){
    Route::post('get-all-queues', [QueueController::class, 'getAll'])->name('getAll');
});

Route::name('rooms')->group(function(){
    Route::post('get-all-rooms', [RoomController::class, 'getAll'])->name('getAll');
});