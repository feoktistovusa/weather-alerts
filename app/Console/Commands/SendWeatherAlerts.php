<?php

namespace App\Console\Commands;

use App\Mail\WeatherAlertMail;
use App\Models\Subscriber;
use App\Services\WeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeatherAlerts extends Command
{
    protected $signature = 'weather:send-alerts';
    protected $description = 'Send weather alerts to subscribers';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(WeatherService $weatherService)
    {
        $subscribers = Subscriber::all();

        foreach ($subscribers as $subscriber) {
            $city = $subscriber->city;
            $weather = $weatherService->getCurrentWeather($city);

            $alertMessage = $this->checkForAlerts($weather);

            if ($alertMessage) {
                $alertData = [
                    'city'    => $city,
                    'message' => $alertMessage,
                ];

                Mail::to($subscriber->email)->send(new WeatherAlertMail($alertData));
            }
        }

        return Command::SUCCESS;
    }

    private function checkForAlerts(array $weather): ?string
    {
        $alerts = [];

        // Check for high precipitation
        if (isset($weather['rain']) && $weather['rain']['1h'] > 10) {
            $alerts[] = 'High precipitation expected.';
        }

        // Check for harmful UV rays
        if (isset($weather['coord'])) {
            $uvData = app(WeatherService::class)->getUVIndex($weather['coord']['lat'], $weather['coord']['lon']);
            if ($uvData['value'] > 6) {
                $alerts[] = 'High UV index detected.';
            }
        }

        return $alerts ? implode(' ', $alerts) : null;
    }
}
