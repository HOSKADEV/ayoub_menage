<?php

namespace App\Http\Resources\CategorySubcategory;

use Illuminate\Http\Resources\Json\JsonResource;

class IdsResource extends JsonResource
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
          // "id" => $this->id,
          // 'pivot' => $this->pivot,
          'category_id' => $this->pivot->category_id,
          'subcategory_id' => $this->pivot->subcategory_id,
        ];
    }
}
