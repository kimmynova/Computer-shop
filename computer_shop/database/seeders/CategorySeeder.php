<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            [
                'name' => 'computer',
                'description' => 'We have many computers',
            ],
            [
                'name' => 'laptop',
                'description' => 'We have many laptops',
            ],
            [
                "name" => "peripheral",
                "description" => "We have many peripheral"
            ]
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }
    }
}
