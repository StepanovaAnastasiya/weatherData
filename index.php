<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use Weather\DataProviders\FileCitiesDataProvider;
use Weather\DataProviders\OpenWeatherMapDataProvider;
use Weather\Services\AppLogger;
use Weather\Controllers\WeatherController;

$controller = new WeatherController(new OpenWeatherMapDataProvider( new FileCitiesDataProvider(), $cachePath, $apiKey), new AppLogger());
$controller->viewWeatherData();
