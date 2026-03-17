<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeCheckoutController extends Controller
{
    public function create(Package $package)
    {
        if (! $package->is_active || ! $package->hasPrice()) {
            abort(404);
        }

        $key = Setting::getConfig('services.stripe.key');
        if (empty($key)) {
            return redirect()->route('packages.show', $package)
                ->with('error', 'Payments are not configured. Please contact us for a quote.');
        }

        Stripe::setApiKey(Setting::getConfig('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => Setting::getConfig('services.stripe.currency', 'usd'),
                    'product_data' => [
                        'name' => $package->name,
                        'description' => Str::limit($package->description ?? '', 500),
                    ],
                    'unit_amount' => $package->price_cents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'metadata' => [
                'package_id' => (string) $package->id,
            ],
        ]);

        Payment::updateOrCreate(
            ['stripe_id' => $session->id],
            [
                'package_id' => $package->id,
                'amount_cents' => $package->price_cents,
                'email' => '',
                'status' => 'pending',
            ]
        );

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->route('packages.index')->with('error', 'Invalid session.');
        }

        $secret = Setting::getConfig('services.stripe.secret');
        if (empty($secret)) {
            return view('checkout.success', ['session_id' => $sessionId, 'payment' => null, 'package' => null]);
        }

        Stripe::setApiKey($secret);
        try {
            $session = StripeSession::retrieve($sessionId);
        } catch (\Exception $e) {
            return redirect()->route('packages.index')->with('error', 'Could not verify payment.');
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('packages.index')->with('error', 'Payment was not completed.');
        }

        $payment = Payment::where('stripe_id', $sessionId)->first();
        $package = $payment?->package;

        return view('checkout.success', ['session_id' => $sessionId, 'payment' => $payment, 'package' => $package]);
    }

    public function cancel()
    {
        return redirect()->route('packages.index')->with('info', 'Checkout was cancelled.');
    }

    public function webhook(Request $request)
    {
        $secret = Setting::getConfig('services.stripe.webhook_secret');
        if (empty($secret)) {
            return response()->json(['error' => 'Webhook not configured'], 400);
        }

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $payment = Payment::where('stripe_id', $session->id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'email' => $session->customer_email ?? $session->customer_details->email ?? '',
                ]);
            }
        }

        return response()->json(['received' => true]);
    }
}
