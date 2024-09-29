<?php

namespace App\Console\Commands;

use App\Mail\WeatherAlertMail;
use App\Models\Subscriber;
use App\Services\WeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWeatherAlerts extends Command
{
    protected $signature = 'weather:send-alerts';
    protected $description = 'Send weather alerts to subscribers';

    public function handle(WeatherService $weatherService)
    {
        $subscribers = Subscriber::all();

        if ($subscribers->isEmpty()) {
            $this->info('No subscribers found.');
            return self::SUCCESS;
        }

        foreach ($subscribers as $subscriber) {
            $this->processSubscriber($subscriber, $weatherService);
        }

        return self::SUCCESS;
    }

    private function processSubscriber(Subscriber $subscriber, WeatherService $weatherService): void
    {
        $email = $subscriber->email;
        $city  = $subscriber->city;

        $this->info("Processing subscriber: {$email}");
        $this->info("Fetching weather for city: {$city}");

        try {
            $weather      = $weatherService->getCurrentWeather($city);
            $alertMessage = $this->generateAlertMessage($weather);

            if ($alertMessage) {
                $this->sendAlert($email, $city, $alertMessage);
                $this->info("Email sent to: {$email}");
            } else {
                $this->info("No alerts for {$email} in {$city}");
            }
        } catch (\Exception $e) {
            $this->error("Error processing {$email}: " . $e->getMessage());
            Log::error("Error processing subscriber", [
                'email'     => $email,
                'city'      => $city,
                'exception' => $e,
            ]);
        }
    }

    private function generateAlertMessage(array $weather): ?string
    {
        $alerts = [];

        if ($this->hasHighPrecipitation($weather)) {
            $alerts[] = 'High precipitation expected.';
        }

        if ($this->hasHarmfulUVIndex($weather)) {
            $alerts[] = 'High UV index detected.';
        }

        return $alerts ? implode(' ', $alerts) : null;
    }

    private function hasHighPrecipitation(array $weather): bool
    {
        return isset($weather['rain']['1h']) && $weather['rain']['1h'] > 10;
    }

    private function hasHarmfulUVIndex(array $weather): bool
    {
        if (!isset($weather['coord']['lat'], $weather['coord']['lon'])) {
            return false;
        }

        $uvData = app(WeatherService::class)->getUVIndex(
            $weather['coord']['lat'],
            $weather['coord']['lon']
        );

        return isset($uvData['value']) && $uvData['value'] > 6;
    }

    private function sendAlert(string $email, string $city, string $message): void
    {
        $alertData = [
            'city'    => $city,
            'message' => $message,
        ];

        Mail::to($email)->send(new WeatherAlertMail($alertData));
    }
}
