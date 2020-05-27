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
			'user' => new UserResource($this->whenLoaded('user')),
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
			'comments' => CommentResource::collection($this->whenLoaded('comments'))
		];
	}
}