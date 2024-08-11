<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $symbols = isset($this['symbols']) ? json_decode($this['symbols'], true) : [];

        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'symbols' => $symbols,
            'published_at' => $this['published_at'],
        ];
    }
}
