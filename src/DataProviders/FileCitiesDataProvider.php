<?php

namespace Weather\DataProviders;

use Weather\Interfaces\CitiesDataProvider;

class FileCitiesDataProvider implements CitiesDataProvider
{
    public function getCitiesData(): array
    {
        $filePath = 'src/files/cities.dat';
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($lines)) {
            throw new \Exception('Data about cities is absent');
        }
        $header = str_getcsv(array_shift($lines), ' ');

        $data = [];
        foreach ($lines as $line) {
            $values = str_getcsv($line, ' ');
            $countryCode = $values[0] ?? '';
            $zipCode = $values[1] ?? '';
            $city = implode(' ', array_slice($values, 2, -2)) ?? '';
            $longitude = (float)$values[count($values) - 2];
            $latitude = (float)$values[count($values) - 1];
            $data[] = [
                'country_code' => $countryCode,
                'zip_code' => $zipCode,
                'city_name' => $city,
                'longitude' => $longitude,
                'latitude' => $latitude,
            ];
        }
        return $data;
    }
}
