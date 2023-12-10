<?php

namespace Weather\Interfaces;

interface WeatherDataProvider
{
    public function getWeatherData(float $longitude, float $latitude) : array;
}
