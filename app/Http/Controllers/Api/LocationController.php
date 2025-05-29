<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DonationLocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LocationController extends Controller
{
  /**
   * Get nearest donation locations based on user coordinates
   */
  public function getNearestLocations(Request $request): JsonResponse
  {
    try {
      $validated = $request->validate([
        'latitude'  => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'limit'     => 'nullable|integer|min:1|max:50',
      ]);

      $latitude  = $validated['latitude'];
      $longitude = $validated['longitude'];
      $limit     = $validated['limit'] ?? 10;

      $nearestLocations = DonationLocation::getNearestLocations($latitude, $longitude, $limit);

      $response = $nearestLocations->map(function ($location) {
        return [
          'id'              => $location->id,
          'name'            => $location->location_name,
          'address'         => $location->address,
          'city'            => $location->city,
          'latitude'        => $location->latitude,
          'longitude'       => $location->longitude,
          'distance'        => round($location->distance, 2),
          'phone'           => $location->phone,
          'operating_hours' => $location->operating_hours,
        ];
      });

      return response()->json([
        'success'       => true,
        'data'          => $response,
        'user_location' => [
          'latitude'  => $latitude,
          'longitude' => $longitude,
        ],
      ]);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors'  => $e->errors(),
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'An error occurred while fetching locations',
        'error'   => config('app.debug') ? $e->getMessage() : 'Internal server error',
      ], 500);
    }
  }

  /**
   * Get all donation locations grouped by city
   */
  public function getLocationsByCity(): JsonResponse
  {
    try {
      $locations = DonationLocation::active()
        ->select('id', 'location_name', 'address', 'city', 'latitude', 'longitude', 'phone', 'operating_hours')
        ->orderBy('city')
        ->orderBy('location_name')
        ->get()
        ->groupBy('city');

      return response()->json([
        'success' => true,
        'data'    => $locations,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'An error occurred while fetching locations',
        'error'   => config('app.debug') ? $e->getMessage() : 'Internal server error',
      ], 500);
    }
  }

  /**
   * Search locations by name or address
   */
  public function searchLocations(Request $request): JsonResponse
  {
    try {
      $validated = $request->validate([
        'query'     => 'required|string|min:2|max:100',
        'city'      => 'nullable|string|max:50',
        'latitude'  => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
      ]);

      $query     = $validated['query'];
      $city      = $validated['city'] ?? null;
      $latitude  = $validated['latitude'] ?? null;
      $longitude = $validated['longitude'] ?? null;

      $locations = DonationLocation::active()
        ->where(function ($q) use ($query) {
          $q
            ->where('location_name', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%");
        })
        ->when($city, function ($q) use ($city) {
          return $q->where('city', $city);
        })
        ->get();

      // Calculate distance if user coordinates provided
      if ($latitude && $longitude) {
        $locations = $locations->map(function ($location) use ($latitude, $longitude) {
          $location->distance = $location->calculateDistanceFrom($latitude, $longitude);
          return $location;
        })->sortBy('distance');
      }

      $response = $locations->map(function ($location) {
        $data = [
          'id'              => $location->id,
          'name'            => $location->location_name,
          'address'         => $location->address,
          'city'            => $location->city,
          'latitude'        => $location->latitude,
          'longitude'       => $location->longitude,
          'phone'           => $location->phone,
          'operating_hours' => $location->operating_hours,
        ];

        if (isset($location->distance)) {
          $data['distance'] = round($location->distance, 2);
        }

        return $data;
      });

      return response()->json([
        'success'     => true,
        'data'        => $response,
        'query'       => $query,
        'total_found' => $locations->count(),
      ]);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors'  => $e->errors(),
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'An error occurred while searching locations',
        'error'   => config('app.debug') ? $e->getMessage() : 'Internal server error',
      ], 500);
    }
  }
}
