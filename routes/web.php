<?php

use Illuminate\Support\Facades\Route;
use App\Mail\WeatherAlertMail;
use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    $alertData = [
        'city'    => 'London',
        'message' => 'High precipitation levels detected.',
    ];

    Mail::to('user@example.com')->send(new WeatherAlertMail($alertData));

    return 'Email sent!';
});
