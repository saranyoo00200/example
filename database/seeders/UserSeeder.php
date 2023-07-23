<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data_list = [
            [
                'name' => 'dev',
                'email' => 'admin@admin.com',
            ],
        ];

        foreach ($data_list as $data) {
            User::firstOrCreate(
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                ],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('123456'),
                ]
            );
        }
    }
}
