<?php

namespace Database\Seeders;

use App\Models\BloodStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloodStockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodStocks = [
            ['blood_type_id' => 1, 'total_quantity' => 0],
            ['blood_type_id' => 2, 'total_quantity' => 0],
            ['blood_type_id' => 3, 'total_quantity' => 0],
            ['blood_type_id' => 4, 'total_quantity' => 0],
            ['blood_type_id' => 5, 'total_quantity' => 0],
            ['blood_type_id' => 6, 'total_quantity' => 0],
            ['blood_type_id' => 7, 'total_quantity' => 0],
            ['blood_type_id' => 8, 'total_quantity' => 0],
        ];

        foreach ($bloodStocks as $stock) {
            BloodStock::updateOrCreate(
                ['blood_type_id' => $stock['blood_type_id']],
                $stock
            );
        }
    }
}
