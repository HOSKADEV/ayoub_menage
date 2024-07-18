<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $discount = $this->discount();

        return [
          'id' => $this->id,
          'subcategory_id' => $this->subcategory_id,
          'category_id' => $this->subcategory->category_id,
          'supplier_id' => $this->supplier_id,
          'name_supplers' => is_null($this->supplier) ? null : $this->supplier->fullname,
          'unit_name' => $this->unit_name,
          'pack_name' => $this->pack_name,
          'purchasing_price' => $this->purchasing_price,
          'unit_price' => $this->unit_price,
          'pack_price' => $this->pack_price,
          'pack_units' => $this->pack_units,
          'unit_type' => $this->unit_type,
          'stock' => $this->quantity,
          'status' => $this->status,
          'description' => $this->description,
          'code_supplier' => $this->code_supplier,
          'code_bar' => $this->code_bar,
          'is_discounted' => is_null($discount) ? false : true,
          'discount_amount' => is_null($discount) ? 0 : $discount->amount,
          'start_date' => is_null($discount) ? null : $discount->start_date,
          'end_date' => is_null($discount) ? null : $discount->end_date,
          'in_cart' => empty($this->in_cart()) ? false : true,
          'quantity' => $this->in_cart(),
          'videos'  => new ProductMediaCollection($this->videos()),
          'images' => new ProductMediaCollection($this->images()),
          // 'videos'  => empty($this->videos) ? null : url($this->videos),
          // 'image' => empty($this->image) ? null : url($this->image),
          // 'productsMedia' =>  ProdectsMediaResource::collection($this->productMedia)
        ];
    }
}
