<?php

namespace App\Http\Resources;

use App\Http\Resources\Districts\DistrictResource;
use App\Http\Resources\wilayas\WilayaResource;
use App\Http\Resources\wilayas\WilayasCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
        'id'        => $this->id,
        'cart_id'   => $this->cart_id,
        'wilayas_id' => $this->wilayas_id,
        'delivery_price' => $this->delivery_price,
        'phone'     => $this->phone(),
        'longitude' => $this->longitude,
        'latitude'  => $this->latitude,
        'image'     => empty($this->image) ? null : url($this->image),
        'payement_method' => $this->payement_method,
        'ccp_acount'  => $this->ccp_acount,
        'status'      => $this->status,
        'created_at'  => date_format($this->created_at,'Y-m-d H:i:s'),
        'updated_at'  => date_format($this->updated_at,'Y-m-d H:i:s'),
        'client' => new ClientResource($this->client),
        'wilayas' => new WilayaResource($this->wilayas),
        'district' => new DistrictResource($this->district),
        'invoice' => is_null($this->invoice) ? null :new InvoiceResource($this->invoice),
      ];
    }
}
