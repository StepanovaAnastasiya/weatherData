<?php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$cachePath = __DIR__ . '/cache/';
$apiKey = $_ENV['API_KEY'] ?? '';
