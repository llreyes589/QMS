<?php

use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::name('queues')->group(function(){
    Route::get('queues', [QueueController::class, 'index'])->name('index');
});

