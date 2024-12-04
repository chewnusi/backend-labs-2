<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class KeycloakMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Token not provided'], Response::HTTP_UNAUTHORIZED);
        }
        try {
            $publicKey = env('KEYCLOAK_PUBLIC_KEY');
            if (!$publicKey) {
                return response()->json(['error' => 'Public key not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            Log::info('Loaded public key for validation');
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));
            $userRoles = array_merge(
                $decoded->realm_access->roles ?? [],
                $decoded->resource_access->{"node-app"}->roles ?? []
            );
            Log::info('Decoded roles: ' . implode(', ', $userRoles));
            if (!empty(array_intersect($roles, $userRoles))) {
                return $next($request); 
            }
            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            Log::error('Token validation error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid token', 'details' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }
}