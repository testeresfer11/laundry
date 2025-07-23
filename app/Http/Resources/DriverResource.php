<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,  
            'name'          => $this->first_name.' '.$this->last_name,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'email'         => $this->email,
            'lang'          => $this->lang,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'profile'       => ($this->driverDetail && $this->driverDetail->profile) ? $this->driverDetail->profile : null,
            'phone_number'  => ($this->driverDetail && $this->driverDetail->phone_number) ? $this->driverDetail->phone_number : null,
            'gender'        => ($this->driverDetail && $this->driverDetail->gender) ? $this->driverDetail->gender : null,
            'country_code'      => ($this->driverDetail && $this->driverDetail->country_code) ? $this->driverDetail->country_code : null,
            'country_short_code'=> ($this->driverDetail && $this->driverDetail->country_short_code) ? $this->driverDetail->country_short_code : null,
            'dob'               => ($this->driverDetail && $this->driverDetail->dob) ? $this->driverDetail->dob : null,
        ];
    }
}
