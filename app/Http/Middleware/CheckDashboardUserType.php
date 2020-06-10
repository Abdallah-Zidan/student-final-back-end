<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;

class CheckDashboardUserType
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
		if ($request->user()->type === UserType::getTypeString(UserType::MODERATOR) ||
			$request->user()->type === UserType::getTypeString(UserType::ADMIN))
			return $next($request);

		return response([], 401);
	}
}