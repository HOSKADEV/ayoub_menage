<?php

namespace App\Http\Resources\Districts;

use App\Http\Resources\wilayas\WilayaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
          'id'        => $this->id,
          'name'      => $this->name,
          'display_name' => $this->display_name,
          'latitude'  => $this->latitude,
          'longitude' => $this->longitude,
          'wilaya_id' => $this->wilaya_id,
          // 'wilaya'    => $this->wilayaDis->name
        ];
    }
}
