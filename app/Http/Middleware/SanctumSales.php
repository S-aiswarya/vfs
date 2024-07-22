<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanctumSales
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->user()->tokenCan('role:sales') || auth()->user()->tokenCan('role:manager') || auth()->user()->tokenCan('role:super-admin')) {
            return $next($request);
        }
        return response()->json(['message' => 'Not Authorized'], 401);
    }
}