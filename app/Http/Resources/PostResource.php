<?php

namespace App\Http\Resources;

use App\Enums\PostScope;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
			'body' => $this->body,
			'reported' => $this->reported,
			'year' => $this->when($this->year, $this->year),
			$this->mergeWhen($this->whenLoaded('user'), [
				'user' => [
					'id' => $this->user->id,
					'name' => $this->user->name,
					'avatar' => $this->user->avatar
				]
			]),
			$this->mergeWhen(
				$this->scope === PostScope::getScopeString(PostScope::DEPARTMENT),
				['department_faculty' => new DepartmentFacultyResource($this->whenLoaded('scopeable'))]
			),
			$this->mergeWhen(
				$this->scope === PostScope::getScopeString(PostScope::FACULTY),
				['faculty' => new FacultyResource($this->whenLoaded('scopeable'))]
			),
			$this->mergeWhen(
				$this->scope === PostScope::getScopeString(PostScope::UNIVERSITY),
				['university' => new UniversityResource($this->whenLoaded('scopeable'))]
			),
			'comments' => CommentResource::collection($this->whenLoaded('comments')),
			'files' => FileResource::collection($this->whenLoaded('files')),
			'created_at' => $this->created_at,
			'created_at_human' => $this->created_at->diffForHumans()
		];
	}
}