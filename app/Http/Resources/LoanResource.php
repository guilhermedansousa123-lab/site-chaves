<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
            'key_id' => $this->key_id,
            'key' => new KeyResource($this->whenLoaded('chave')),
            'person' => $this->person,
            'note' => $this->note,
            'loaned_at' => $this->loaned_at,
            'returned_at' => $this->returned_at,
            'status' => $this->returned_at ? 'returned' : 'open',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
