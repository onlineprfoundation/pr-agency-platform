<?php

namespace App\Http\Middleware;

use App\Services\InstallService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UseFileSessionDuringInstall
{
    public function __construct(
        protected InstallService $installService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->installService->isInstalled() && str_starts_with($request->path(), 'install')) {
            config(['session.driver' => 'file']);
        }

        return $next($request);
    }
}
