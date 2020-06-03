<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
			$this->mergeWhen($this->whenLoaded('user'), [
				'user' => [
					'id' => $this->user->id,
					'name' => $this->user->name,
					'avatar' => $this->user->avatar
				]
			]),
			'replies' => CommentResource::collection($this->whenLoaded('replies')),
			$this->mergeWhen($this->whenLoaded('rates'), [
				'rates' => $this->rates()->sum('rate'),
				"rated" =>  $this->is_rated()
			]),
			'created_at' => $this->created_at,
			'created_at_human' => $this->created_at->diffForHumans()
		];
	}
	
	/**
	 * Comment Rated before or none
	 *
	 * @return 0 not rated , 1 or -1 rated
	 */
	private function is_rated()
	{
		$rate = $this->rates()->find( Request()->user()->id);
		return $rate ? $rate->pivot->rate : 0;
	}
}
