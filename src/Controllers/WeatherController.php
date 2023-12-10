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


    public function __construct(WeatherDataProvider $dataProvider, AppLogger $appLogger)
    {
        $this->dataProvider = $dataProvider;
        $this->appLogger = $appLogger;
    }

    public function viewWeatherData()
    {
        if (isset($_POST['submit'])) {
            $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
            $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);
            try {
                $this->validateInput($latitude, $longitude);
                $data = $this->dataProvider->getWeatherData($latitude, $longitude);
            } catch (\Exception $e) {
                $error = 'Error while processing the data: ' . $e->getMessage();
                $this->appLogger->logger->error($error);
            }

        }
        require("src/templates/weatherView.php");
    }

    private function validateInput($latitude, $longitude)
    {
        if (empty($latitude) || empty($longitude)) {
            throw new \InvalidArgumentException('Invalid data in form fields, please enter numeric values');
        }
    }
}
