<?php

// 1. Perbaikan Model DonationLocation
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationLocation extends Model
{
    use HasFactory;

    // Gunakan struktur fillable yang sudah ada
    protected $fillable = [
        'location_name',
        'location_detail',
        'address',
        'url_address',
        'cover',
        'city',
        'latitude',
        'longitude'
    ];

    // Tambahkan default values jika diperlukan
    protected $attributes = [
        'location_detail' => 'Lokasi Donor Darah',
    ];

    public function donations()
    {
        return $this->hasMany(Donations::class);
    }

    public function calculateDistanceFrom($latitude, $longitude): float
    {
        if (!$this->latitude || !$this->longitude) {
            return 0;
        }

        return $this->haversineDistance(
            $latitude,
            $longitude,
            $this->latitude,
            $this->longitude
        );
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $R    = 6371;  // Radius bumi dalam kilometer
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
                * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public static function getNearestLocations($latitude, $longitude, $limit = null)
    {
        $locations = self::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $locationsWithDistance = $locations->map(function ($location) use ($latitude, $longitude) {
            $location->distance = $location->calculateDistanceFrom($latitude, $longitude);
            return $location;
        });

        $sorted = $locationsWithDistance->sortBy('distance');

        return $limit ? $sorted->take($limit) : $sorted;
    }

    // TAMBAHAN: Method untuk mencari atau membuat lokasi baru
    public static function findOrCreateByPlaceId($placeId, $data = [])
    {
        // Karena tidak ada field place_id, kita bisa gunakan url_address untuk menyimpan place_id
        return self::firstOrCreate(
            ['url_address' => $placeId],
            $data
        );
    }

    public static function findOrCreateByName($locationName, $data = [])
    {
        return self::firstOrCreate(
            ['location_name' => $locationName],
            array_merge($data, ['location_name' => $locationName])
        );
    }
}

// 2. Perbaikan Method Submit di Class Donations
