<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

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
			'rates'=>$this->whenLoaded('rates') instanceof MissingValue ? new MissingValue : $this->rates()->sum('rate'),
			'rated'=>$this->whenLoaded('rates') instanceof MissingValue ? new MissingValue : $this->isRated(),
			'created_at' => $this->created_at,
			'created_at_human' => $this->created_at->diffForHumans()
		];
	}
	
	/**
	 * Comment is rated by the user before or not
	 *
	 * @return int 0 if not rated, 1 or -1 if rated
	 */
	private function isRated()
	{
		$rate = $this->rates()->find( Request()->user()->id);
		return $rate ? $rate->pivot->rate : 0;
	}
}
