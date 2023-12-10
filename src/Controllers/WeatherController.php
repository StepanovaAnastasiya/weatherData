<?php

namespace Weather\Controllers;

use Weather\Services\AppLogger;
use Weather\DataProviders\FileCitiesDataProvider;
use Weather\DataProviders\OpenWeatherMapDataProvider;
use Weather\Interfaces\WeatherDataProvider;


class WeatherController
{

    private WeatherDataProvider $dataProvider;
    private AppLogger $appLogger;


    public function __construct()
    {
        $this->dataProvider = new OpenWeatherMapDataProvider( new FileCitiesDataProvider());
        $this->appLogger = new AppLogger();
    }

    function viewWeatherData()
    {
        if (isset($_POST['submit'])) {
            $latitude = htmlspecialchars($_POST['latitude']);
            $longitude = htmlspecialchars($_POST['longitude']);
            if(!is_numeric($latitude) || !is_numeric($longitude)){
                $error = 'Invalid data in form fields, please enter numeric values';
            } else {
                try{
                    $data = $this->dataProvider->getWeatherData($latitude, $longitude);
                } catch (\Exception $e){
                    $error = 'Error while processing the data: ' . $e->getMessage();
                    $this->appLogger->logger->error($error);
                }
            }
        }
        require("src/templates/weatherView.php");
    }
}