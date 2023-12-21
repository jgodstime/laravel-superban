<?php
namespace LaravelSuperBan\SuperBan\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelSuperBan\SuperBan\SuperBan;
use Vendor\Superban\RateLimiter;

class SuperbanMiddleware
{
    protected $limiter;

    public function __construct(SuperBan $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, ...$parameters)
    {
        // Extract parameters from route middleware string (e.g., "200,2,1440")
        $params = explode(',', $parameters[0]);
        list($throttle, $duration, $ban) = $params + [null, null, config('superban.ban_duration')];

        // Get route and identifier (IP by default)
        $route = Route::current()->uri();
        $identifier = $request->ip();

        // Check if request exceeds limit or is banned
        if ($this->limiter->check($route, $identifier, $throttle, $ban)) {
            // Return configured response or exception
            if (config('superban.banned_response')) {
                return response(config('superban.banned_response'), 429);
            }

            throw new \Exception('You have been banned.', 429);
        }

        return $next($request);
    }
}
