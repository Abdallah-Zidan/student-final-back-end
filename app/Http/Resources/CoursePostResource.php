<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoursePostResource extends JsonResource
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
            'id'=>$this->id,
            'body'=>$this->body,
            $this->mergeWhen($this->whenLoaded('user'), [
				'user' => [
					'id' => $this->user->id,
					'name' => $this->user->name,
					'avatar' => $this->user->avatar
				]
            ]),
            'files' => FileResource::collection($this->whenLoaded('files')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'course_department_faculty'=>new CourseDepartmentFacultyResource($this->whenLoaded('courseDepartmentFaculty')),
            'created_at'=>$this->created_at,
            'created_at_human'=>$this->created_at->diffForHumans(),

        ];
    }
}
