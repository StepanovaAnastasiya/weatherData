<?php

namespace Weather\Controllers;

use Weather\DataProviders\FileCitiesDataProvider;
use Weather\DataProviders\OpenWeatherMapDataProvider;
use Weather\Interfaces\WeatherDataProvider;


class WeatherController
{

    private WeatherDataProvider $dataProvider;


    public function __construct()
    {
        $this->dataProvider = new OpenWeatherMapDataProvider( new FileCitiesDataProvider());
    }

    function viewWeatherData()
    {
        if (isset($_POST['submit'])) {
            $latitude = htmlspecialchars($_POST['latitude']);
            $longitude = htmlspecialchars($_POST['longitude']);
            if(!is_numeric($latitude) || !is_numeric($longitude)){
                $error = 'Invalid data in form fields, please enter numeric values';
            } else {
                $data = $this->dataProvider->getWeatherData($latitude, $longitude);
            }
        }
        require("src/templates/weatherView.php");
    }
}