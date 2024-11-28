<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'phone' => $this->phone,
          'district_id' => $this->district_id,
          'wilaya_id' => $this->district->wilaya_id,
          'district_name' => $this->district->display_name,
          'wilaya_name' => $this->district->wilayaDis->display_name,
          'image' => empty($this->image) ? null : url($this->image),
          'longitude' => $this->longitude,
          'latitude'  => $this->latitude,
        ];
    }
}
