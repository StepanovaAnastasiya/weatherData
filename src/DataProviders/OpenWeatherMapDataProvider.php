<?php
namespace Weather\DataProviders;

use Weather\Interfaces\CitiesDataProvider;
use Weather\Interfaces\WeatherDataProvider;
use Weather\Traits\DistanceBetweenSpots;

class OpenWeatherMapDataProvider implements WeatherDataProvider
{

    private CitiesDataProvider $citiesDataProvider;

    use DistanceBetweenSpots;

    private $cachePath;

    public function __construct(CitiesDataProvider $citiesDataProvider, $cachePath = __DIR__ . '/../../cache/')
    {
        $this->citiesDataProvider = $citiesDataProvider;
        $this->cachePath = $cachePath;
    }


    public function getWeatherData(float $latitude, float $longitude) : array
    {
        $citiesArray = $this->citiesDataProvider->getCitiesData();
        $readyData = [];

        foreach ($citiesArray as $city => $data) {
            $cacheKey = md5($data['zip_code'] . $data['country_code']);
            $cachedData = $this->getCachedWeatherData($cacheKey);

            if (!$cachedData) {
                $APIurl = $this->getAPIurl($data);
                $rawCityWeatherData = $this->getCityWeatherData($APIurl);

                $this->cacheWeatherData($cacheKey, $rawCityWeatherData);
            } else {
                $rawCityWeatherData = $cachedData;
            }

            $readyData[] = $this->processRawWeatherData($data, $rawCityWeatherData, $latitude, $longitude);
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

    private function getCachedWeatherData($cacheKey)
    {
        $cacheFilePath = $this->cachePath . $cacheKey;

        if (file_exists($cacheFilePath)) {
            $cacheData = file_get_contents($cacheFilePath);
            return json_decode($cacheData, true);
        }

        return null;
    }

    private function cacheWeatherData($cacheKey, $rawCityWeatherData)
    {
        $cacheFilePath = $this->cachePath . $cacheKey;

        if (!is_dir(dirname($cacheFilePath))) {
            if (!mkdir($concurrentDirectory = dirname($cacheFilePath), 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        file_put_contents($cacheFilePath, json_encode($rawCityWeatherData));
    }
}
