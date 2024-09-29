<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.openweathermap.org/data/2.5/';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    public function getCurrentWeather(string $city): array
    {
        $response = Http::get($this->baseUrl . 'weather', [
            'q'     => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
        ]);

        return $response->json();
    }

    public function getUVIndex(float $lat, float $lon): array
    {
        $response = Http::get($this->baseUrl . 'uvi', [
            'lat'   => $lat,
            'lon'   => $lon,
            'appid' => $this->apiKey,
        ]);

        return $response->json();
    }
}
