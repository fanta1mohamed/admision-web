<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cepre
{
    public function handle($request, Closure $next)
    {
        $allowedIPs = [];

        if (in_array($request->ip(), $allowedIPs)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $request->header('Origin'))
                ->header('Access-Control-Allow-Methods', 'GET')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
        }

        // https://hoppscotch.io
        $allowedDomains = ['https://sistemas.cepreuna.edu.pe/','https://test.cepreuna.edu.pe/'];

        if (in_array($request->header('Origin'), $allowedDomains)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $request->header('Origin'))
                ->header('Access-Control-Allow-Methods', 'GET')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
