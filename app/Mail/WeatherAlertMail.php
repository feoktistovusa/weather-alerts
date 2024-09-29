<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class WeatherAlertMail extends Mailable
{
    public array $alertData;

    public function __construct(array $alertData)
    {
        $this->alertData = $alertData;
    }

    public function build()
    {
        return $this->subject('Weather Alert Notification')
            ->view('emails.weather_alert');
    }
}
