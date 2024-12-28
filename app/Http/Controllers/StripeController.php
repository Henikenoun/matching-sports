<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function processpayment(Request $request)
    {
        try {
            Stripe::setApiKey('sk_test_51N8h05Ffd7KAMX0K7IK7ESWrghlttNb89k7B7tiI5tGpVKzqHzzR2ARH3yPdIJJNlCPNOpxKA8MmMEGxYLzWBsgF009T79Ts7j');
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $request->line_items,
                'mode' => 'payment',
                'success_url' => $request->success_url,
                'cancel_url' => $request->cancel_url,
            ]);
            return response()->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
