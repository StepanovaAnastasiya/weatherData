<?php

namespace Weather\Interfaces;

interface WeatherDataProvider
{
    public function getWeatherData($longitude, $latitude);
}
