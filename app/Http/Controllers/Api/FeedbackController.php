<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\FeedbackEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'body' => 'required|string',
        ]);

        $customerServiceEmail = config('custom.customer-service-email');

        Mail::to($customerServiceEmail)->send(new FeedbackEmail(
            $attributes['name'],
            $attributes['email'],
            $attributes['body']
        ));

        return response()->json(['status' => 'success'], 200);
    }
}
