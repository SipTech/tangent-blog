<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\userResource;
use App\Http\Resources\Api\V1\postResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'user' => new UserResource($this->whenLoaded('user')),
            'post' => new postResource($this->whenLoaded('post')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
