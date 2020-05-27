<?php

namespace App\Http\Middleware;

use App\Comment;
use App\Post;
use Closure;

class CheckCommentOwner
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
        if ($this->checkCommentOwner($request) || $this->checkPostOwner($request)) {
            return $next($request);
        }

        return response([], 400);
    }

    /**
     * check if request user is the owner of the post
     *
     * @param  Request $request
     * @return bool
     */
    protected function checkPostOwner($request)
    {
        $post = Post::findOrFail($request->post);
        return $request->user()->id === $post->user->id && $request->method() == "DELETE";
    }

    /**
     * check if request user is the owner of the comment
     *
     * @param  Request $request
     * @return bool
     */
    private function checkCommentOwner($request)
    {
        $comment = Comment::findOrFail($request->comment);
        return $request->user()->id === $comment->user->id;
    }
}
