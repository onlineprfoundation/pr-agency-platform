<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class InvoiceController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'amount_cents' => 'required|integer|min:100',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
        ]);

        $invoice = Invoice::create([
            'project_id' => $project->id,
            'amount_cents' => $request->amount_cents,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => 'draft',
        ]);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Invoice created.');
    }

    public function sendPaymentLink(Project $project, Invoice $invoice)
    {
        if ($invoice->project_id !== $project->id) {
            abort(404);
        }

        $key = Setting::getConfig('services.stripe.key');
        if (empty($key)) {
            return redirect()->route('admin.projects.show', $project)
                ->with('error', 'Stripe is not configured.');
        }

        Stripe::setApiKey(Setting::getConfig('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => Setting::getConfig('services.stripe.currency', 'usd'),
                    'product_data' => [
                        'name' => 'Invoice #' . $invoice->id . ' – ' . $project->name,
                        'description' => $invoice->description ?? 'Project invoice',
                    ],
                    'unit_amount' => $invoice->amount_cents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('invoice.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('admin.projects.show', $project),
            'metadata' => [
                'invoice_id' => (string) $invoice->id,
                'project_id' => (string) $project->id,
            ],
        ]);

        $invoice->update([
            'status' => 'sent',
            'stripe_invoice_id' => $session->id,
            'stripe_payment_link' => $session->url,
        ]);

        Payment::create([
            'project_id' => $project->id,
            'invoice_id' => $invoice->id,
            'amount_cents' => $invoice->amount_cents,
            'email' => $project->client->email,
            'stripe_id' => $session->id,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.projects.show', $project)
            ->with('success', 'Payment link created. Share: ' . $session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->route('home')->with('error', 'Invalid session.');
        }

        $payment = Payment::where('stripe_id', $sessionId)->first();
        if ($payment && $payment->invoice_id) {
            $payment->invoice->update(['status' => 'paid']);
        }

        return view('invoice.success');
    }
}
