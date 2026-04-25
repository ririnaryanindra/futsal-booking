<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Field;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Field::create(['name' => 'Lapangan A', 'price_per_hour' => 100000]);
        Field::create(['name' => 'Lapangan B', 'price_per_hour' => 120000]);
    }
}
