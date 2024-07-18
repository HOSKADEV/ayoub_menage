<?php

namespace App\Http\Resources\settings;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'price_max' => $this->price_max,
            'account_bankily' => $this->bank_account_bankily,
            'account_sedad'   => $this->bank_account_sedad,
            'account_bimbank' => $this->bank_account_bimbank,
            'account_masrfy'  => $this->bank_account_masrfy,
        ];
    }
}
