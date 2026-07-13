<?php

namespace App\Http\Middleware;

use App\Models\Rider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRider
{
    public function handle(Request $request, Closure $next): Response
    {
        $rider = $request->user();

        if (! $rider instanceof Rider || ! $rider->tokenCan('rider')) {
            abort(403, 'Akses hanya untuk rider.');
        }

        if ($rider->account_status !== 'Aktif') {
            $rider->currentAccessToken()?->delete();
            abort(403, 'Akun rider tidak aktif.');
        }

        return $next($request);
    }
}
