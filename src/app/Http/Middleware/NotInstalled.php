<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Settings;

class NotInstalled
{

    public function handle($request, Closure $next)
    {

        if (Settings::isInstalled()) {
			return redirect('/');
        }
		return $next($request);
    }
}
