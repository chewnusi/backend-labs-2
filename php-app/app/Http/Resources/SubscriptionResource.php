<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'subscriber_id' => $this->subscriber_id,
            'service' => $this->service,
            'topic' => $this->topic,
            'payload' => $this->payload,
            'expired_at' => $this->expired_at,
            'subscriber' => new SubscriberResource($this->whenLoaded('subscriber')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
