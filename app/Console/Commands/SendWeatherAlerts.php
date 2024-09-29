<?php

namespace App\Console\Commands;

use App\Mail\WeatherAlertMail;
use App\Models\Subscriber;
use App\Models\User;
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
        $users = User::with('cities')->get();

        if ($users->isEmpty()) {
            $this->info('No users found.');
            return self::SUCCESS;
        }

        foreach ($users as $user) {
            $this->processUser($user, $weatherService);
        }

        return self::SUCCESS;
    }

    private function processUser(User $user, WeatherService $weatherService): void
    {
        $email = $user->email;
        $this->info("Processing user: {$email}");

        foreach ($user->cities as $city) {
            $cityName = $city->name;
            $this->info("Fetching weather for city: {$cityName}");

            try {
                $weather      = $weatherService->getCurrentWeather($cityName);
                $alertMessage = $this->generateAlertMessage($weather, $user);

                if ($alertMessage) {
                    $this->sendAlert($email, $cityName, $alertMessage);
                    $this->info("Email sent to: {$email}");
                } else {
                    $this->info("No alerts for {$email} in {$cityName}");
                }
            } catch (\Exception $e) {
                $this->error("Error processing {$email} for city {$cityName}: " . $e->getMessage());
                Log::error("Error processing user", [
                    'email'     => $email,
                    'city'      => $cityName,
                    'exception' => $e,
                ]);
            }
        }
    }

    private function generateAlertMessage(array $weather, User $user): ?string
    {
        $alerts = [];

        if ($this->hasHighPrecipitation($weather, $user)) {
            $alerts[] = 'High precipitation expected.';
        }

        if ($this->hasHarmfulUVIndex($weather, $user)) {
            $alerts[] = 'High UV index detected.';
        }

        return $alerts ? implode(' ', $alerts) : null;
    }

    private function hasHighPrecipitation(array $weather, User $user): bool
    {
        return isset($weather['rain']['1h']) && $weather['rain']['1h'] > $user->precipitation_threshold;
    }

    private function hasHarmfulUVIndex(array $weather, User $user): bool
    {
        if (!isset($weather['coord']['lat'], $weather['coord']['lon'])) {
            return false;
        }

        $uvData = app(WeatherService::class)->getUVIndex(
            $weather['coord']['lat'],
            $weather['coord']['lon']
        );

        return isset($uvData['value']) && $uvData['value'] > $user->uv_index_threshold;
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
