<?php

namespace Weather\Traits;

trait DistanceBetweenSpots
{
    public function calculateDistance(float $latitude1, float $longitude1, float $latitude2, float $longitude2) : float
    {
        $earthRadius = 6371;

        $latFrom = deg2rad($latitude1);
        $lonFrom = deg2rad($longitude1);
        $latTo = deg2rad($latitude2);
        $lonTo = deg2rad($longitude2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distance in kilometers
        return $earthRadius * $c;
    }
}
