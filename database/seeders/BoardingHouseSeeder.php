<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\BoardingHouse;

class BoardingHouseSeeder extends Seeder
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
                'bhouse_name' => 'DACLES BOARDING HOUSE',
                'bhouse_desc' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae quasi eligendi deserunt, magni sequi asperiores.',
                'user_id' => 2,
                'bhouse_rule' => 'SAMPLE BHOUSE RULE',
                'bhouse_img_path' => 'bhouse01.jpg',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215025',
                'street' => 'JUAN LUNA ST p-BOUGAINVILLA',
                'lat' => '8.061954385341004',
                'long' => '123.75697910785675',
                'is_approve' => 0
            ],
            [
                'bhouse_name' => 'FUENTES BOARDING HOUSE',
                'bhouse_desc' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae quasi eligendi deserunt, magni sequi asperiores.',
                'user_id' => 3,
                'bhouse_rule' => 'SAMPLE BHOUSE RULE',
                'bhouse_img_path' => 'bhouse02.jpg',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215025',
                'street' => 'JUAN LUNA ST p-BOUGAINVILLA',
                'lat' => '8.063680587142077',
                'long' => '123.75273048877715',
                'is_approve' => 0
            ],
            [
                'bhouse_name' => 'SIETE BOARDING HOUSE',
                'bhouse_desc' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae quasi eligendi deserunt, magni sequi asperiores.',
                'user_id' => 4,
                'bhouse_rule' => 'SAMPLE BHOUSE RULE',
                'bhouse_img_path' => 'bhouse03.jpg',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215025',
                'street' => 'JUAN LUNA ST p-BOUGAINVILLA',
                'lat' => '8.061486981739758',
                'long' => '123.75372290611267',
                'is_approve' => 0
            ],
            [
                'bhouse_name' => 'TAGOBAR BOARDING HOUSE',
                'bhouse_desc' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae quasi eligendi deserunt, magni sequi asperiores.',
                'user_id' => 5,
                'bhouse_rule' => 'SAMPLE BHOUSE RULE',
                'bhouse_img_path' => 'bhouse04.jpg',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215025',
                'street' => 'JUAN LUNA ST p-BOUGAINVILLA',
                'lat' => '8.063834617098614',
                'long' => '123.75342249870299',
                'is_approve' => 0
            ],
        ];

        BoardingHouse::insertOrIgnore($data);

    }
}
