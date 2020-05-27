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
			$this->mergeWhen($this->whenLoaded('user'), [
				'user' => [
					'id' => $this->user->id,
					'name' => $this->user->name,
					'avatar' => $this->user->avatar
				]
			]),
			$this->mergeWhen(
				$this->whenLoaded('scopeable') && $this->scope === PostScope::getScopeString(PostScope::DEPARTMENT),
				['department_faculty' => new DepartmentFacultyResource($this->scopeable)]
			),
			$this->mergeWhen(
				$this->whenLoaded('scopeable') && $this->scope === PostScope::getScopeString(PostScope::FACULTY),
				['faculty' => new FacultyResource($this->scopeable)]
			),
			$this->mergeWhen(
				$this->whenLoaded('scopeable') && $this->scope === PostScope::getScopeString(PostScope::UNIVERSITY),
				['university' => new UniversityResource($this->scopeable)]
			),
			'comments' => CommentResource::collection($this->whenLoaded('comments')),
			'files' => FileResource::collection($this->whenLoaded('files'))
		];
	}
}