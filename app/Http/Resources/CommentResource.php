<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'body' => $this->body,
			'user' => new UserResource($this->whenLoaded('user')),
			'replies' => CommentResource::collection($this->whenLoaded('replies'))
		];
	}
}