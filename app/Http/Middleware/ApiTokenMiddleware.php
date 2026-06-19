<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $plainToken = $request->bearerToken();

        if (! $plainToken) {
            if ($request->acceptsHtml() && ! $request->wantsJson()) {
                return redirect('/login')->with('error', 'This is a protected API route. Use the website pages after logging in, or use Postman with a Bearer token.');
            }

            return response()->json([
                'message' => 'Missing Bearer token. Login at POST /api/v1/login first.',
                'note' => 'Website pages use session login. API write/protected routes use Bearer token for Postman/VS Code only.',
            ], 401);
        }

        $hashedToken = hash('sha256', $plainToken);

        $token = ApiToken::with('user')
            ->where('token', $hashedToken)
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $token || ! $token->user) {
            if ($request->acceptsHtml() && ! $request->wantsJson()) {
                return redirect('/login')->with('error', 'Invalid or expired API token. Use the normal website login for browser pages.');
            }

            return response()->json(['message' => 'Invalid or expired token.'], 401);
        }

        $token->forceFill(['last_used_at' => now()])->save();
        Auth::setUser($token->user);
        $request->setUserResolver(fn () => $token->user);
        $request->attributes->set('api_token_id', $token->id);

        return $next($request);
    }
}
