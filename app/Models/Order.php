<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'user_id',
      'cart_id',
      'wilayas_id',
      'district_id',
      'phone',
      'delivery_price',
      'image',
      'payement_method',
      'ccp_acount',
      'status',
      'longitude',
      'latitude',
      'note',
      'file',
    ];

    protected $casts = [
      'user_id' => 'integer',
      'cart_id' => 'integer',
      'wilayas_id' => 'integer',
      'district_id' => 'integer',
      'delivery_price' => 'double',
      'ccp_acount' => 'integer',
      'longitude' => 'double',
      'latitude' => 'double',
    ];

    protected $softCascade = ['invoice','delivery'];

    public function user(){
      return $this->belongsTo(User::class);
    }

    public function cart(){
      return $this->belongsTo(Cart::class);
    }

    public function wilayas()
    {
      return $this->belongsTo(Wilaya::class);
    }

    public function district()
    {
      return $this->belongsTo(District::class,'district_id');
    }

    public function items(){
      return $this->cart->items;
    }

    public function invoice(){
      return $this->hasOne(Invoice::class);
    }

    public function delivery(){
      return $this->hasOne(Delivery::class);
    }

    public function phone(){
      /* return is_null($this->phone) ? null : '0'.$this->phone; */
      return $this->phone;
    }

    public function address()
    {
      return 'https://maps.google.com/?q='.$this->longitude.','.$this->latitude;
    }

}
