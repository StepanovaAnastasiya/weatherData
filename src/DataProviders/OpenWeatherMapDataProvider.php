<?php
namespace Weather\DataProviders;

use Weather\Interfaces\CitiesDataProvider;
use Weather\Interfaces\WeatherDataProvider;
use Weather\Traits\DistanceBetweenSpots;

class OpenWeatherMapDataProvider implements WeatherDataProvider
{

    private CitiesDataProvider $citiesDataProvider;

    use DistanceBetweenSpots;


    public function __construct(CitiesDataProvider $citiesDataProvider)
    {
        $this->citiesDataProvider = $citiesDataProvider;
    }

    public function getWeatherData(float $latitude, float $longitude) : array
    {
        $citiesArray = $this->citiesDataProvider->getCitiesData();
        $readyData = [];
        foreach ($citiesArray as $city => $data) {
            $APIurl = $this->getAPIurl($data);
            $readyData[] = $this->processRawWeatherData($data, $this->getCityWeatherData($APIurl), $latitude, $longitude);
        }

        usort($readyData, [$this, 'sortByTempSpread']);

        return $readyData;
    }

    private function getCityWeatherData($APIurl) {
        $json = file_get_contents($APIurl);
        return json_decode($json, true);
    }

    private function getAPIurl($data)
    {
        return 'https://api.openweathermap.org/data/2.5/weather?zip=' . $data['zip_code'] . ',' . $data['country_code']. '&units=metric&appid=' . $_ENV['API_KEY'];
    }

    private function processRawWeatherData($fileData, $rawCityWeatherData, $latitude, $longitude)
    {
        $cityData = [];
        $cityData['city_name'] = $rawCityWeatherData['name'] ?? $fileData['city_name'];
        $cityData['temp_min'] = $rawCityWeatherData['main']['temp_min'];
        $cityData['temp_max'] = $rawCityWeatherData['main']['temp_max'];
        $cityData['temp_spread'] = $cityData['temp_max'] - $cityData['temp_min'];
        $cityData['distance_to_spot'] = $this->calculateDistance($latitude, $longitude, $rawCityWeatherData['coord']['lat'] ?? $fileData['latitude'], $rawCityWeatherData['coord']['lon'] ?? $fileData['longitude']);
        $cityData['weather_description'] = $rawCityWeatherData['weather'][0]['description'];
        $cityData['icon'] = $rawCityWeatherData['weather'][0]['icon'];
        return $cityData;
    }

    private function sortByTempSpread($a, $b)
    {
        return $a['temp_spread'] <=> $b['temp_spread'];
    }
}
