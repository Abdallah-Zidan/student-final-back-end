<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ToolResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'body' => $this->body,
			'type' => $this->type,
			$this->mergeWhen($this->whenLoaded('user'), [
				'user' => [
					'id' => $this->user->id,
					'name' => $this->user->name,
					'avatar' => $this->user->avatar
				]
			]),
			'faculty' => new FacultyResource($this->whenLoaded('faculty')),
			'comments' => CommentResource::collection($this->whenLoaded('comments')),
			'files' => FileResource::collection($this->whenLoaded('files')),
			'tags' => TagResource::collection($this->whenLoaded('tags')),
			'created_at' => $this->created_at,
			'created_at_human' => $this->created_at->diffForHumans()
		];
	}
}