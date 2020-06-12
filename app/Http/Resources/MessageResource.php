<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'text'=>$this->text,
            'from2'=>$this->mergeWhen($this->whenLoaded('sender'), [
				'user' => [
					'id' => $this->sender->id,
					'name' => $this->sender->name,
					'avatar' => $this->sender->avatar
				]
			]),
            'to2'=>$this->mergeWhen($this->whenLoaded('receiver'), [
				'user' => [
					'id' => $this->receiver->id,
					'name' => $this->receiver->name,
					'avatar' => $this->receiver->avatar
				]
            ]),
            'to'=>$this->receiver,
            'from'=>$this->sender,
            'created_at'=>$this->created_at,
            'created_at_human' => $this->created_at->diffForHumans()
        ];
    }
}
