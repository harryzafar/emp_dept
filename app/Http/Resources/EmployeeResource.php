<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email'      => $this->email,
            'designation'   => $this->designation,
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'phone_numbers' => $this->phoneNumbers->pluck('phone'),
            'addresses_1'     => $this->addresses->pluck('line1'),
            'addresses_2'     => $this->addresses->pluck('line2'),
            'city'     => $this->addresses->pluck('city'),
            'state'     => $this->addresses->pluck('state'),
            'postal_code'     => $this->addresses->pluck('postal_code'),
            'label'     => $this->addresses->pluck('label'),
        ];
    }
}
