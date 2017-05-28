<?php

namespace App\Http\Middleware;

use App\Http\Traits\RedirectRequests;
use Closure;

class CheckIfAdmin
{
    use RedirectRequests;

    /**
     * Check if user is admin or redirect to homepage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->user()->isAdmin()) {
            return $this->sendErrorResponse('Unauthorized Access', 'home');
        }

        return $next($request);
    }
}
