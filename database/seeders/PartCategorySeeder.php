<?php

namespace Database\Seeders;

use App\Models\PartCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PartCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Màn hình',
            'Pin',
            'Bo mạch',
            'Cáp sạc',
            'Loa',
            'Micro',
            'Camera',
            'Bộ nhớ',
            'CPU',
            'RAM',
            'SSD',
            'Bàn phím',
        ] as $sortOrder => $name) {
            PartCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'sort_order' => $sortOrder,
                    'is_active' => true,
                ],
            );
        }
    }
}
