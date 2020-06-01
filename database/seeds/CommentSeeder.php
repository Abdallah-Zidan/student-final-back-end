<?php

use App\Comment;
use App\Event;
use App\Post;
use App\Question;
use App\Tool;
use App\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Post::all()->each(function ($post) {
			User::inRandomOrder()->take(3)->get()->each(function ($user) use ($post) {
				$comment = factory(Comment::class)->create([
					'user_id' => $user->id,
					'parentable_type' => get_class($post),
					'parentable_id' => $post->id
				]);
				User::inRandomOrder()->take(2)->get()->each(function ($user) use ($comment) {
					factory(Comment::class)->create([
						'user_id' => $user->id,
						'parentable_type' => get_class($comment),
						'parentable_id' => $comment->id
					]);
				});
			});
		});

		Event::all()->each(function ($event) {
			User::inRandomOrder()->take(3)->get()->each(function ($user) use ($event) {
				$comment = factory(Comment::class)->create([
					'user_id' => $user->id,
					'parentable_type' => get_class($event),
					'parentable_id' => $event->id
				]);
				User::inRandomOrder()->take(2)->get()->each(function ($user) use ($comment) {
					factory(Comment::class)->create([
						'user_id' => $user->id,
						'parentable_type' => get_class($comment),
						'parentable_id' => $comment->id
					]);
				});
			});
		});

		Question::all()->each(function ($question) {
			User::inRandomOrder()->take(3)->get()->each(function ($user) use ($question) {
				factory(Comment::class)->create([
					'user_id' => $user->id,
					'parentable_type' => get_class($question),
					'parentable_id' => $question->id
				]);
			});
		});

		Tool::all()->each(function ($tool) {
			User::inRandomOrder()->take(3)->get()->each(function ($user) use ($tool) {
				factory(Comment::class)->create([
					'user_id' => $user->id,
					'parentable_type' => get_class($tool),
					'parentable_id' => $tool->id
				]);
			});
		});
	}
}