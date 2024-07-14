<?php

namespace App\Http\Resources\Districts;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DistrictCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public $collects = DistrictResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
