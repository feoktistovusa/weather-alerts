<?php

namespace Tests\Unit;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    /** @test */
    public function it_can_fetch_current_weather()
    {
        Http::fake([
            '*' => Http::response(['weather' => 'data'], 200),
        ]);

        $service = new WeatherService();
        $weather = $service->getCurrentWeather('London');

        $this->assertEquals(['weather' => 'data'], $weather);
    }
}

