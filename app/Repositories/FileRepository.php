<?php

namespace App\Repositories;

use App\Enums\FileResource;
use App\Event;
use App\File;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Storage;

class FileRepository
{
	public function create(User $current_user, array $data)
	{
		if ($data['resource'] == FileResource::POST)
		{
			$post = Post::find($data['resource_id']);

			if ($post && $post->user->id === $current_user->id)
			{
				$path = Storage::disk('local')->put('files/posts/' . $post->id, $data['file']);
				$mime = Storage::mimeType($path);

				return $post->files()->create([
					'path' => $path,
					'mime' => $mime
				]);
			}
		}
		else if ($data['resource'] == FileResource::EVENT)
		{
			$event = Event::find($data['resource_id']);

			if ($event && $event->user->id === $current_user->id)
			{
				$path = Storage::disk('local')->put('files/events/' . $event->id, $data['file']);
				$mime = Storage::mimeType($path);

				return $event->files()->create([
					'path' => $path,
					'mime' => $mime
				]);
			}
		}

		return false;
	}

	public function delete(User $current_user, File $file)
	{
		if ($file->resourceable->user->id === $current_user->id)
		{
			$file->delete();

			return true;
		}

		return false;
	}
}