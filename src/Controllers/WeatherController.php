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
            $longitude = htmlspecialchars('longitude');
            $latitude = htmlspecialchars('latitude');
            $data = $this->dataProvider->getWeatherData($longitude, $latitude);
        }
        require("src/templates/weatherView.php");
    }
}