<?php

namespace Database\Seeders;

use App\Models\BedSpace;
use Illuminate\Database\Seeder;

class BedSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'room_id' => 1,
                'bhouse_id' => 1,
                'bedspace_name' => 'BED SPACE B1',
                'bedspace_desc' => 'to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.',
                'bedspace_img_path' => '',
                'price' => 1000,
                'is_booked' => 0
            ],
        ];

        BedSpace::insertOrIgnore($data);
    }
}
