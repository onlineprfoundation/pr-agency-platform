<?php

namespace App\Http\Middleware;

use App\Services\InstallService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstalled
{
    public function __construct(
        protected InstallService $installService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Ensure .env exists for first-time setup (use file session until DB is ready)
        $envPath = base_path('.env');
        if (! File::exists($envPath) && File::exists(base_path('.env.example'))) {
            $content = File::get(base_path('.env.example'));
            $content = preg_replace('/^SESSION_DRIVER=.*/m', 'SESSION_DRIVER=file', $content);
            $content = preg_replace('/^CACHE_STORE=.*/m', 'CACHE_STORE=file', $content);
            File::put($envPath, $content);
        }

        if (! $this->installService->isInstalled()) {
            if ($request->routeIs('install.*')) {
                return $next($request);
            }

            return redirect()->route('install.welcome');
        }

        if ($request->routeIs('install.*')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
