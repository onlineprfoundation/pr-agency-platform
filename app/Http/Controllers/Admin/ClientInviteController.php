<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ClientInviteController extends Controller
{
    public function store(Client $client)
    {
        $existing = User::where('client_id', $client->id)->first();
        if ($existing) {
            return redirect()->route('admin.clients.show', $client)
                ->with('info', 'This client already has portal access.');
        }

        $existingEmail = User::where('email', $client->email)->first();
        if ($existingEmail) {
            $existingEmail->update(['client_id' => $client->id, 'role' => 'client']);
            Password::sendResetLink(['email' => $client->email]);
            return redirect()->route('admin.clients.show', $client)
                ->with('success', 'Linked existing user to client. Password reset sent to ' . $client->email);
        }

        User::create([
            'name' => $client->name,
            'email' => $client->email,
            'password' => Hash::make(Str::random(32)),
            'client_id' => $client->id,
            'role' => 'client',
        ]);

        Password::sendResetLink(['email' => $client->email]);

        return redirect()->route('admin.clients.show', $client)
            ->with('success', 'Portal invite sent to ' . $client->email . '. They can set their password via the reset link.');
    }
}
