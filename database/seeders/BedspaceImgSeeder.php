<?php

namespace Database\Seeders;


use App\Models\BedspaceImg;
use Illuminate\Database\Seeder;

class BedspaceImgSeeder extends Seeder
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
                'bedspace_id' => 1,
                'bedspace_img_path' => 'bedspace01.jpg',
            ],
            [
                'bedspace_id' => 1,
                'bedspace_img_path' => 'bedspace02.jpg',
            ],
            [
                'bedspace_id' => 1,
                'bedspace_img_path' => 'bedspace03.jpg',
            ],
            [
                'bedspace_id' => 1,
                'bedspace_img_path' => 'bedspace04.jpg',
            ],
        ];

        BedspaceImg::insertOrIgnore($data);
    }
}
