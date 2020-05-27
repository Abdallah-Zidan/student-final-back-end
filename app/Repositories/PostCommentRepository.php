<?php

namespace App\Repositories;

use App\Comment;
use App\Http\Resources\CommentResource;
use App\Post;

class PostCommentRepository
{
    /**
     * Create new comment
     *
     * @param  int $user_id
     * @param int $post_id
     * @param string $body
     * @return respose
     */
    public function create($user_id, $post_id, string $body)
    {
        Post::findOrFail($post_id)->comments()->create([
            'body' => $body,
            'user_id' => $user_id
        ]);
        return response([], 201);
    }

    /**
     * Update Comment
     *
     * @param int  $comment_id
     * @param string $body
     * @return response
     */
    public function update($comment_id, string $body)
    {
        $comment = Comment::findOrFail($comment_id);
        $comment->update(['body' => $body]);
        return response([], 204);
    }

    /**
     * Delete Comment
     *
     * @param int $comment_id
     * @return response
     */
    public function delete($comment_id)
    {
        Comment::findOrFail($comment_id)->delete();
        return response([], 204);
    }

    /**
     * Get all Post Comments
     *
     * @param int $post_id
     * @return void
     */
    public function getAllPostComments($post_id)
    {
        $post = Post::findOrFail($post_id);
        $comments = $post->comments()->with(['user', 'replies', 'replies.user'])->paginate(10);
        return CommentResource::collection($comments);
    }
}
