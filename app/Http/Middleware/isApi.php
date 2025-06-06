<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $api_key   = $request->header('X-Authorization');

        if ($api_key !== '2RzCaqlEenJjzkj2tMkK3YPEea68qcLy') {
            $response = [
                'code'      => 400,
                'message'   => 'UNAUTHORIZED',
                'data'      => []
            ];
            return response()->json($response, 200);
        }

        return $next($request);
    }
}
