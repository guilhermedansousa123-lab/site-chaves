<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'place' => $this->place,
            'status' => $this->status,
            'active_loans_count' => $this->whenCounted('active_loans'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
