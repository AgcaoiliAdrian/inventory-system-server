<?php

namespace App\Http\Middleware;

use Closure;

class CheckBranchAssigned
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
        // Get the user information from the request
        $userInfo = $request->user()->info;

        // Check if the user's branch is 'Head Office'
        if ($userInfo->branch_assigned !== 'Head Office') {
            // If not 'Head Office', return unauthorized response
            return response()->json(['message' => 'Unauthorized. Only Head Office employees can access this route.'], 403);
        }

        // If the user's branch is 'Head Office', proceed to the next middleware or route
        return $next($request);
    }
}
