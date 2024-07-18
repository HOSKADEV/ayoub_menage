<?php

namespace App\Http\Resources\CategorySubcategory;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubcategoryResource;
use App\Models\Subcategory;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorySubcategoryResource extends JsonResource
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
          'id' => $this->id,
          'category' =>  $this->category,
          'subcategory' => new SubcategoryResource($this->subcategory),
          // 'category_id' => $this->category_id,
          // 'subcategory_id' => $this->subcategory_id,
        ];
    }
}
