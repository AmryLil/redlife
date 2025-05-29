<?php

namespace Database\Seeders;

use App\Models\DonationLocation;
use Illuminate\Database\Seeder;

class DonationLocationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $locations = [
      [
        'location_name' => 'PMI Kota Makassar',
        'address'       => 'Jl. Jenderal Sudirman No.1, Baru, Ujung Pandang, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1477851,
        'longitude'     => 119.4327314,
      ],
      [
        'location_name' => 'RS Wahidin Sudirohusodo',
        'address'       => 'Jl. Perintis Kemerdekaan Km.11, Tamalanrea, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1285967,
        'longitude'     => 119.4889294,
      ],
      [
        'location_name' => 'RS Akademis UGM',
        'address'       => 'Jl. Perintis Kemerdekaan Km.10, Tamalanrea Indah, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1312455,
        'longitude'     => 119.4886507,
      ],
      [
        'location_name' => 'RS Stella Maris',
        'address'       => 'Jl. Somba Opu No.273, Losari, Ujung Pandang, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1358671,
        'longitude'     => 119.4035199,
      ],
      [
        'location_name' => 'RS Siloam Makassar',
        'address'       => 'Jl. Metro Tanjung Bunga, Tanjung Merdeka, Tamalate, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1547108,
        'longitude'     => 119.4128892,
      ],
      [
        'location_name' => 'RS Hermina Makassar',
        'address'       => 'Jl. Urip Sumoharjo No.43, Karampuang, Panakkukang, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1515539,
        'longitude'     => 119.4392738,
      ],
      [
        'location_name' => 'RS Awal Bros Makassar',
        'address'       => 'Jl. Urip Sumoharjo No.43, Karampuang, Panakkukang, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1545622,
        'longitude'     => 119.4301147,
      ],
      [
        'location_name' => 'PMI Kabupaten Gowa',
        'address'       => 'Jl. Sultan Hasanuddin, Sungguminasa, Gowa',
        'city'          => 'Gowa',
        'latitude'      => -5.2070979,
        'longitude'     => 119.4411525,
      ],
      [
        'location_name' => 'RS Labuang Baji',
        'address'       => 'Jl. Dr. Ratulangi No.81, Labuang Baji, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1286753,
        'longitude'     => 119.4223874,
      ],
      [
        'location_name' => 'RSUD Daya Makassar',
        'address'       => 'Jl. Perintis Kemerdekaan Km.14, Daya, Biringkanaya, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1158743,
        'longitude'     => 119.5087456,
      ],
      [
        'location_name' => 'RS Ibnu Sina Makassar',
        'address'       => 'Jl. Kumala No.1, Mamajang, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1520815,
        'longitude'     => 119.4182739,
      ],
      [
        'location_name' => 'RS Pelamonia Makassar',
        'address'       => 'Jl. Jend. Urip Sumoharjo No.87, Karampuang, Panakkukang, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1486842,
        'longitude'     => 119.4346123,
      ],
      [
        'location_name' => 'PMI Kabupaten Maros',
        'address'       => 'Jl. Trans Sulawesi, Maros, Sulawesi Selatan',
        'city'          => 'Maros',
        'latitude'      => -5.0066917,
        'longitude'     => 119.5733394,
      ],
      [
        'location_name' => 'RS Bhayangkara Makassar',
        'address'       => 'Jl. Ahmad Yani No.2, Pelita, Ujung Pandang, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1405542,
        'longitude'     => 119.4081886,
      ],
      [
        'location_name' => 'Puskesmas Kassi-Kassi',
        'address'       => 'Jl. Racing Centre, Kassi-Kassi, Rappocini, Makassar',
        'city'          => 'Makassar',
        'latitude'      => -5.1751234,
        'longitude'     => 119.4234567,
      ],
    ];

    foreach ($locations as $location) {
      DonationLocation::updateOrCreate(
        [
          'location_name' => $location['location_name'],
          'city'          => $location['city']
        ],
        $location
      );
    }
  }
}
