<?php

use Illuminate\Support\Facades\Route;
use App\Services\WeatherService;

Route::get('/test-weather', function (WeatherService $weatherService) {
    $city = 'London';
    $weather = $weatherService->getCurrentWeather($city);

    dd($weather);
});
