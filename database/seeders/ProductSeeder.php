<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data_list = [
            [
                'name' => 'น้ำเปล่า',
                'status' => 1,
                'shop_id' => 1,
            ],
            [
                'name' => 'น้ำเปล่าอัดลม',
                'status' => 1,
                'shop_id' => 1,
            ],
            [
                'name' => 'เครื่องดื่มแอลกอฮอล์',
                'status' => 1,
                'shop_id' => 2,
            ],
        ];

        //---------------------------- insert --------------------------------------------------------
        foreach ($data_list as $data) {
            Product::firstOrCreate(
                [
                    'name' => $data['name'],
                ],
                [
                    'name' => $data['name'],
                    'status' => $data['status'],
                    'shop_id' => $data['shop_id'],
                ]
            );
        }
    }
}
