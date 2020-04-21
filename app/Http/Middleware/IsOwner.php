<?php

namespace App\Http\Middleware;

use Closure;

class IsOwner
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
        /** @var App\Models\User $user */
        $user = auth('api')->user();
        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        $model = $request->user;
        if ($user && $model && $user->id == $model->id) {
            return $next($request);
        }

        if ($request->wantsJson()) {
            return response('Unauthorized', 403);
        }

        abort(403, 'Unauthorized');
    }
}
