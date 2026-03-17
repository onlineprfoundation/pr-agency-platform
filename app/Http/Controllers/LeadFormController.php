<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Publication;
use App\Models\PublicationOrder;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;

class LeadFormController extends Controller
{
    public function contact()
    {
        return view('contact', [
            'recaptchaEnabled' => RecaptchaService::isRequiredFor('contact'),
            'recaptchaSiteKey' => RecaptchaService::getSiteKey(),
        ]);
    }

    public function storeContact(Request $request)
    {
        if (RecaptchaService::isRequiredFor('contact')) {
            $request->validate(['g-recaptcha-response' => 'required']);
            if (! RecaptchaService::verify($request->input('g-recaptcha-response'))) {
                return redirect()->route('contact')
                    ->withInput()
                    ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.']);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        Lead::create([
            ...$validated,
            'source' => 'contact',
            'status' => 'new',
        ]);

        return redirect()->route('contact')->with('success', 'Thank you! We will get back to you soon.');
    }

    public function quote(Request $request)
    {
        $publication = null;
        if ($request->publication_id) {
            $publication = Publication::find($request->publication_id);
        }
        return view('quote', [
            'recaptchaEnabled' => RecaptchaService::isRequiredFor('quote'),
            'recaptchaSiteKey' => RecaptchaService::getSiteKey(),
            'publication' => $publication,
        ]);
    }

    public function storeQuote(Request $request)
    {
        if (RecaptchaService::isRequiredFor('quote')) {
            $request->validate(['g-recaptcha-response' => 'required']);
            if (! RecaptchaService::verify($request->input('g-recaptcha-response'))) {
                return redirect()->route('quote')
                    ->withInput()
                    ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.']);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:5000',
            'package_interest' => 'nullable|string|max:255',
            'publication_interest' => 'nullable|string|max:255',
            'publication_id' => 'nullable|exists:publications,id',
        ]);

        $message = $validated['message'] ?? '';
        if (! empty($validated['package_interest'] ?? '')) {
            $message = "Package interest: {$validated['package_interest']}\n\n" . $message;
        }
        if (! empty($validated['publication_interest'] ?? '')) {
            $message = ($message ? $message . "\n\n" : '') . "Publication interest: {$validated['publication_interest']}";
        }

        Lead::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,
            'message' => $message,
            'source' => 'quote',
            'status' => 'new',
        ]);

        if (! empty($validated['publication_id'] ?? null)) {
            $publication = Publication::find($validated['publication_id']);
            if ($publication) {
                $order = PublicationOrder::create([
                    'publication_id' => $publication->id,
                    'client_id' => auth()->check() ? auth()->user()->client_id : null,
                    'email' => $validated['email'],
                    'source' => 'quote_request',
                    'status' => 'pending_submission',
                    'access_token' => PublicationOrder::generateAccessToken(),
                ]);
                return redirect()->route('publication-orders.show', $order->access_token)
                    ->with('success', 'Thank you! We will send you a quote soon. You can submit your content below.');
            }
        }

        return redirect()->route('quote')->with('success', 'Thank you! We will send you a quote soon.');
    }
}
