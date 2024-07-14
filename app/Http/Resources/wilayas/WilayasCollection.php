<?php

namespace App\Http\Resources\wilayas;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WilayasCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public $collects = WilayaResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
