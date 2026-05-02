<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->hasActiveSubscription()) {
            return redirect()->route('subscription.index')
                ->with('error', 'Anda perlu berlangganan untuk menonton film ini.');
        }

        return $next($request);
    }
}
