<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

Route::get('/', [SubscriptionController::class, 'showForm']);
Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
