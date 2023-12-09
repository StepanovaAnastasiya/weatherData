<?php
ini_set( "display_errors", true );
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
require_once("src/Controllers/WeatherController.php");
session_start();
$controller = new Weather\Controllers\WeatherController;
$controller->viewWeatherData();
