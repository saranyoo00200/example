<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run()
    {
        $data_list = [
            [
                'name' => 'rudy shop',
            ],
            [
                'name' => 'rudy shop 2',
            ],
        ];

        //---------------------------- insert --------------------------------------------------------
        foreach ($data_list as $data) {
            Shop::firstOrCreate(
                [
                    'name' => $data['name'],
                ],
                [
                    'name' => $data['name'],
                ]
            );
        }
    }
}
