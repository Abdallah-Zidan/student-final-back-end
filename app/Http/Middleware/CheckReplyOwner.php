<?php

namespace App\Http\Middleware;

use App\Comment;
use Closure;

class CheckReplyOwner extends CheckCommentOwner
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
        if ($this->checkReplyOwner($request) || $this->checkPostOwner($request)) {
            return $next($request);
        }

        return response([], 400);
    }

    private function checkReplyOwner($request)
    {
        $reply = Comment::findOrFail($request->reply);
        return $request->user()->id === $reply->user->id;
    }
}
