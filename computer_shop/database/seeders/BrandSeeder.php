<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Intel', 'Razer', 'Asus'] as $brandName) {
            $brand = [
                'brand_uuid' => Uuid::uuid4()->toString(),
                'brand_name' => $brandName,
            ];

            Brand::create($brand);
        }
    }
}
