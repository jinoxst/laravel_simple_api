<?php

namespace App\Http\Middleware;

use Closure;
use App\BookUser;
use Request;
use Response;

class ApiAuthFilter
{
    /**
     * @var string
     */
    const APPLICATION_TOKEN = 'x-application-token';
    /**
     * @var string
     */
    const AUTHORIZED_USER = 'authorized_user';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = BookUser::where('api_token', Request::header(static::APPLICATION_TOKEN))->first();
        if (is_null($user)) {
            \Log::error('user info is null');
            return Response::json(['message' => '401 Unauthorized'], 401);
        } else {
            \Log::debug('user is [' . $user->id . '] / ' . $user->name);
        }

        app()[static::AUTHORIZED_USER] = $user;

        return $next($request);
    }
}
