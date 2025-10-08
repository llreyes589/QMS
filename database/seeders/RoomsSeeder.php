<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $rooms = ["A2", "A3", "A4", 'A5', 'A6 - MOORES', 'A9', 'A10', 'B2', 'B3', 'B9', 'B4', 'B7', 'B8', 'B1', 'C1', 'C2', 'C3', 'C4', 'C5', 'Treatment', 'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8'];

        foreach ($rooms as $room) {
            Room::create(['room_code' => $room, 'description' => '']);
        }
    }
}
