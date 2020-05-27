<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentFacultyResource extends JsonResource
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
			'department' => new DepartmentResource($this->whenLoaded('department')),
			'faculty' => new FacultyResource($this->whenLoaded('faculty'))
		];
	}
}