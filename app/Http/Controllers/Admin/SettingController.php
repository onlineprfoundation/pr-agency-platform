<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'site_name' => Setting::get('site_name', config('app.name')),
            'tagline' => Setting::get('tagline', ''),
            'contact_email' => Setting::get('contact_email', ''),
            'contact_address' => Setting::get('contact_address', ''),
            'logo_path' => Setting::get('logo_path', ''),
            'favicon_path' => Setting::get('favicon_path', ''),
            'services_stripe_key' => Setting::get('services_stripe_key', ''),
            'services_stripe_currency' => Setting::get('services_stripe_currency', 'usd'),
            'mail_driver' => Setting::get('mail_driver', config('mail.default', 'log')),
            'mail_host' => Setting::get('mail_host', ''),
            'mail_port' => Setting::get('mail_port', '587'),
            'mail_username' => Setting::get('mail_username', ''),
            'mail_encryption' => Setting::get('mail_encryption', 'tls'),
            'mail_from_address' => Setting::get('mail_from_address', config('mail.from.address', '')),
            'mail_from_name' => Setting::get('mail_from_name', config('mail.from.name', '')),
            'home_hero_title' => Setting::get('home_hero_title', ''),
            'home_hero_subtitle' => Setting::get('home_hero_subtitle', ''),
            'home_hero_cta_text' => Setting::get('home_hero_cta_text', ''),
            'home_show_packages' => Setting::get('home_show_packages', '1'),
            'home_show_publications' => Setting::get('home_show_publications', '1'),
            'recaptcha_enabled' => Setting::get('recaptcha_enabled', '0'),
            'recaptcha_site_key' => Setting::get('recaptcha_site_key', ''),
            'recaptcha_secret_key' => Setting::get('recaptcha_secret_key', ''),
            'recaptcha_forms' => Setting::get('recaptcha_forms', 'contact,quote'),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $rules = [
            'site_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email',
            'contact_address' => 'nullable|string|max:500',
            'logo' => 'nullable|file|mimes:jpeg,png,gif,webp,svg|max:2048',
            'favicon' => 'nullable|file|mimes:ico,png,gif,svg|max:512',
            'remove_logo' => 'nullable|boolean',
            'remove_favicon' => 'nullable|boolean',
            'services_stripe_key' => 'nullable|string|max:255',
            'services_stripe_secret' => 'nullable|string|max:255',
            'services_stripe_webhook_secret' => 'nullable|string|max:255',
            'services_stripe_currency' => 'nullable|string|max:10',
            'mail_driver' => 'nullable|string|in:smtp,log,mailgun,ses,postmark,resend,sendmail',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl,none',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string|max:255',
            'home_hero_title' => 'nullable|string|max:255',
            'home_hero_subtitle' => 'nullable|string|max:500',
            'home_hero_cta_text' => 'nullable|string|max:255',
            'home_show_packages' => 'nullable|string|in:0,1',
            'home_show_publications' => 'nullable|string|in:0,1',
            'recaptcha_enabled' => 'nullable|string|in:0,1',
            'recaptcha_site_key' => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
            'recaptcha_forms' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules);

        foreach (['site_name', 'tagline', 'contact_email', 'contact_address', 'services_stripe_key',
            'services_stripe_currency', 'mail_driver', 'mail_host', 'mail_port', 'mail_username',
            'mail_encryption', 'mail_from_address', 'mail_from_name', 'home_hero_title',
            'home_hero_subtitle', 'home_hero_cta_text', 'home_show_packages', 'home_show_publications',
            'recaptcha_enabled', 'recaptcha_site_key', 'recaptcha_forms'] as $key) {
            if (array_key_exists($key, $validated)) {
                Setting::set($key, $validated[$key] ?? '');
            }
        }

        if (! empty($validated['services_stripe_secret'] ?? null)) {
            Setting::set('services_stripe_secret', $validated['services_stripe_secret']);
        }
        if (! empty($validated['services_stripe_webhook_secret'] ?? null)) {
            Setting::set('services_stripe_webhook_secret', $validated['services_stripe_webhook_secret']);
        }
        if (! empty($validated['mail_password'] ?? null)) {
            Setting::set('mail_password', $validated['mail_password']);
        }
        if (! empty($validated['recaptcha_secret_key'] ?? null)) {
            Setting::set('recaptcha_secret_key', $validated['recaptcha_secret_key']);
        }

        if ($request->boolean('remove_logo')) {
            $oldPath = Setting::get('logo_path');
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            Setting::set('logo_path', '');
        } elseif ($request->hasFile('logo')) {
            $oldPath = Setting::get('logo_path');
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo_path', $path);
        }

        if ($request->boolean('remove_favicon')) {
            $oldPath = Setting::get('favicon_path');
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            Setting::set('favicon_path', '');
        } elseif ($request->hasFile('favicon')) {
            $oldPath = Setting::get('favicon_path');
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon_path', $path);
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated.');
    }
}
