<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

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
			'department_faculty' => new DepartmentFacultyResource($this->whenLoaded('departmentFaculty')),
			'comments' => CommentResource::collection($this->whenLoaded('comments'))
		];
	}
}