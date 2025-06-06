<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonationStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => 1, 'status' => 'Pending'],
            ['id' => 2, 'status' => 'Approved'],
            ['id' => 3, 'status' => 'Rejected'],
            ['id' => 4, 'status' => 'In Progress'],
            ['id' => 5, 'status' => 'Collected'],
            ['id' => 6, 'status' => 'Screening'],
            ['id' => 7, 'status' => 'Rejected Blood'],
            ['id' => 8, 'status' => 'Completed'],
        ];

        DB::table('donation_statuses')->insert($statuses);
    }
}
