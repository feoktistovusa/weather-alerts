<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function showForm()
    {
        return view('subscribe');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
            'city'  => 'required|string',
        ]);

        Subscriber::create($request->only('email', 'city'));

        return redirect('/')->with('success', 'You have successfully subscribed to weather alerts.');
    }
}
