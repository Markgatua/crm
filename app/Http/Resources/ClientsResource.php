<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientsResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'industry_id' => $this->industry_id,
            'industry' => $this->industry,
            'website_url' => $this->website_url,
            'comments' => $this->comments,
            'contact_information' => $this->contact_information,
        ];
    }
}
