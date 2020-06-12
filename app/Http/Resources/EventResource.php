<?php

namespace App\Http\Resources;

use App\Enums\EventScope;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
			'start_date' => $this->start_date,
			'start_date_human' => $this->start_date ? $this->start_date->diffForHumans() : null,
			'end_date' => $this->end_date,
			'end_date_human' => $this->end_date ? $this->end_date->diffForHumans() : null,
			$this->mergeWhen($this->whenLoaded('user'), [
				'user' => [
					'id' => $this->user->id,
					'name' => $this->user->name,
					'avatar' => $this->user->avatar
				]
			]),
			$this->mergeWhen(
				$this->scope === EventScope::getScopeString(EventScope::FACULTY),
				['faculty' => new FacultyResource($this->whenLoaded('scopeable'))]
			),
			$this->mergeWhen(
				$this->scope === EventScope::getScopeString(EventScope::UNIVERSITY),
				['university' => new UniversityResource($this->whenLoaded('scopeable'))]
			),
			'comments' => CommentResource::collection($this->whenLoaded('comments')),
			'files' => FileResource::collection($this->whenLoaded('files')),
			'created_at' => $this->created_at,
			'created_at_human' => $this->created_at->diffForHumans()
		];
	}
}