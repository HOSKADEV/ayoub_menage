<?php

namespace App\Http\Resources\settings;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SettingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public $collects = SettingResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
