<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\PublicationOrder;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class PublicationCheckoutController extends Controller
{
    public function create(Publication $publication)
    {
        if (! $publication->hasPrice()) {
            return redirect()->route('publications.show', $publication)
                ->with('error', 'This publication does not have a set price. Please request a quote.');
        }

        $key = Setting::getConfig('services.stripe.key');
        if (empty($key)) {
            return redirect()->route('publications.show', $publication)
                ->with('error', 'Payments are not configured. Please request a quote.');
        }

        Stripe::setApiKey(Setting::getConfig('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => Setting::getConfig('services.stripe.currency', 'usd'),
                    'product_data' => [
                        'name' => 'Publication: ' . $publication->name,
                        'description' => Str::limit($publication->genre . ' – ' . $publication->region, 500) ?: 'Placement on ' . $publication->name,
                    ],
                    'unit_amount' => $publication->price_cents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('publications.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('publications.show', $publication),
            'customer_email' => auth()->check() ? auth()->user()->email : null,
            'metadata' => [
                'publication_id' => (string) $publication->id,
                'client_id' => auth()->check() && auth()->user()->client_id ? (string) auth()->user()->client_id : '',
            ],
        ]);

        Payment::updateOrCreate(
            ['stripe_id' => $session->id],
            [
                'publication_id' => $publication->id,
                'amount_cents' => $publication->price_cents,
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
            return redirect()->route('publications.index')->with('error', 'Invalid session.');
        }

        $secret = Setting::getConfig('services.stripe.secret');
        if (empty($secret)) {
            return redirect()->route('publications.index')->with('error', 'Payment verification not configured.');
        }

        Stripe::setApiKey($secret);
        try {
            $session = StripeSession::retrieve($sessionId);
        } catch (\Exception $e) {
            return redirect()->route('publications.index')->with('error', 'Could not verify payment.');
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('publications.index')->with('error', 'Payment was not completed.');
        }

        $payment = Payment::where('stripe_id', $sessionId)->first();
        if ($payment) {
            $payment->update([
                'status' => 'completed',
                'email' => $session->customer_email ?? $session->customer_details->email ?? $payment->email ?? '',
            ]);
        }

        $order = $this->ensurePublicationOrder($payment, $session);
        if ($order) {
            if (auth()->check()) {
                return redirect()->route('portal.publication-orders.show', $order)->with('success', 'Thank you! Submit your content below.');
            }
            return redirect()->route('publication-orders.show', $order->access_token)->with('success', 'Thank you! Submit your content below.');
        }

        return redirect()->route('publications.index')->with('success', 'Thank you for your purchase!');
    }

    private function ensurePublicationOrder(?Payment $payment, $session): ?PublicationOrder
    {
        if (! $payment || $payment->status !== 'completed' || ! $payment->publication_id) {
            return null;
        }
        $order = $payment->publicationOrder;
        if ($order) return $order;

        $email = $session->customer_email ?? $session->customer_details->email ?? $payment->email ?? '';
        $clientId = ! empty($session->metadata->client_id ?? '') ? (int) $session->metadata->client_id : null;

        return PublicationOrder::create([
            'publication_id' => $payment->publication_id,
            'payment_id' => $payment->id,
            'client_id' => $clientId,
            'email' => $email,
            'source' => 'purchase',
            'status' => 'pending_submission',
            'amount_cents' => $payment->amount_cents,
            'access_token' => PublicationOrder::generateAccessToken(),
        ]);
    }
}
