<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageOrder;
use App\Models\Payment;
use App\Models\PublicationOrder;
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
            'customer_email' => auth()->check() && auth()->user()->email ? auth()->user()->email : null,
            'metadata' => [
                'package_id' => (string) $package->id,
                'client_id' => auth()->check() && auth()->user()->client_id ? (string) auth()->user()->client_id : '',
            ],
        ]);

        Payment::updateOrCreate(
            ['stripe_id' => $session->id],
            [
                'package_id' => $package->id,
                'amount_cents' => $package->price_cents,
                'email' => auth()->check() ? auth()->user()->email : '',
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
        if ($payment) {
            $payment->update([
                'status' => 'completed',
                'email' => $session->customer_email ?? $session->customer_details->email ?? $payment->email ?? '',
            ]);
        }
        $package = $payment?->package;

        $order = $this->ensurePackageOrder($payment, $session);
        if ($order) {
            if (auth()->check()) {
                return redirect()->route('portal.orders.show', $order)->with('success', 'Thank you for your purchase! Submit your content below.');
            }
            return redirect()->route('orders.show', $order->access_token)->with('success', 'Thank you for your purchase! Submit your content below.');
        }

        return view('checkout.success', ['session_id' => $sessionId, 'payment' => $payment, 'package' => $package]);
    }

    private function ensurePackageOrder(?Payment $payment, $session): ?PackageOrder
    {
        if (! $payment || $payment->status !== 'completed') {
            return null;
        }
        $order = $payment->packageOrder;
        if ($order) {
            return $order;
        }
        $email = $session->customer_email ?? $session->customer_details->email ?? $payment->email ?? '';
        $clientId = ! empty($session->metadata->client_id ?? '') ? (int) $session->metadata->client_id : null;
        return PackageOrder::create([
            'payment_id' => $payment->id,
            'package_id' => $payment->package_id,
            'client_id' => $clientId,
            'email' => $email,
            'status' => 'pending_submission',
            'access_token' => PackageOrder::generateAccessToken(),
        ]);
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
                    'email' => $session->customer_email ?? $session->customer_details->email ?? $payment->email ?? '',
                ]);
                $clientId = ! empty($session->metadata->client_id ?? '') ? (int) $session->metadata->client_id : null;
                if ($payment->package_id && ! $payment->packageOrder) {
                    PackageOrder::create([
                        'payment_id' => $payment->id,
                        'package_id' => $payment->package_id,
                        'client_id' => $clientId,
                        'email' => $payment->email,
                        'status' => 'pending_submission',
                        'access_token' => PackageOrder::generateAccessToken(),
                    ]);
                }
                if ($payment->publication_id && ! $payment->publicationOrder) {
                    PublicationOrder::create([
                        'publication_id' => $payment->publication_id,
                        'payment_id' => $payment->id,
                        'client_id' => $clientId,
                        'email' => $payment->email,
                        'source' => 'purchase',
                        'status' => 'pending_submission',
                        'amount_cents' => $payment->amount_cents,
                        'access_token' => PublicationOrder::generateAccessToken(),
                    ]);
                }
            }
        }

        return response()->json(['received' => true]);
    }
}
