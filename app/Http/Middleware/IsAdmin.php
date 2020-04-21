<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth('api')->user();
        /** @var App\Models\User $user */
        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        if ($request->wantsJson()) {
            return response('Unauthorized', 403);
        }

        abort(403, 'Unauthorized');
    }
}
