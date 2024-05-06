<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data = [

            [
                'bhouse_id' => 1,
                'room_no' => 'ROOM 101',
                'room_desc' => 'ROOM HAS 4 BEDSPACES W/ AIRCON AND 2 CR. FREE WIFI INSTALLED.',
                'room_img_path' => 'room1.jpg',
            ],


        ];

        Room::insertOrIgnore($data);

    }
}
