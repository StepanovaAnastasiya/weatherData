<?php
namespace Weather\DataProviders;

use Weather\Interfaces\CitiesDataProvider;
use Weather\Interfaces\WeatherDataProvider;

class OpenWeatherMapDataProvider implements WeatherDataProvider
{

    private CitiesDataProvider $citiesDataProvider;


    public function __construct(CitiesDataProvider $citiesDataProvider)
    {
        $this->citiesDataProvider = $citiesDataProvider;
    }

    private function getCityWeatherData($APIurl) {
        $json = file_get_contents($APIurl);

        $data = json_decode($json, true);
        return $data;
    }

    public function getWeatherData($longitude, $latitude) : array
    {
        $citiesArray = $this->citiesDataProvider->getCitiesData();
        $rawWeatherData = [];
        $readyData = [];
        foreach ($citiesArray as $city => $data){
            $APIurl = $this->getAPIurl($data);
            $rawWeatherData[] = $this->getCityWeatherData($APIurl);
        }
        var_dump($rawWeatherData);
        return $rawWeatherData;
    }

    private function getAPIurl($data)
    {
        return 'https://api.openweathermap.org/data/2.5/weather?zip=' . $data['zip_code'] . ',' . $data['country_code']. '&appid=' . $_ENV['API_KEY'];
    }
}
