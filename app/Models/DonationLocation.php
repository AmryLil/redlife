<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationLocation extends Model
{
    use HasFactory;

    protected $fillable = ['location_name', 'location_detail', 'address', 'url_address', 'cover', 'city', 'latitude',
        'longitude'];

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

    /**
     * Haversine formula to calculate distance between two points
     */
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

    /**
     * Scope to get active locations only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get locations by city
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Get locations ordered by distance from given coordinates
     */
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
}
