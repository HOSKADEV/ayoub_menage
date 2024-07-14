<?php

namespace App\Http\Resources;

use App\Http\Resources\CategorySubcategory\CategorySubcategoryCollection;
use App\Http\Resources\CategorySubcategory\CategorySubcategoryResource;
use App\Http\Resources\CategorySubcategory\IdsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
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
          'category_id' => $this->category_id,
          'image' => empty($this->image) ? null : url($this->image),
          // 'category' => $this->cate,
          'category' => IdsResource::collection($this->cate) ,
        ];
    }
}
