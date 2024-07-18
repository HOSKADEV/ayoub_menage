<?php

namespace App\Http\Resources\wilayas;

use App\Http\Controllers\District\districtControler;
use App\Http\Resources\DiscountResource;
use App\Http\Resources\Districts\DistrictCollection;
use App\Http\Resources\Districts\DistrictResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WilayaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);
        return [
          'id' => $this->id,
          'name' => $this->name,
          'display_name' => $this->display_name,
          'delivery_price' => $this->delivery_price,
          'longitude' => $this->longitude,
          'latitude' => $this->latitude,
          'district' =>  DistrictResource::collection($this->district),
          // 'district' => $this->district,
        ];
    }
}
