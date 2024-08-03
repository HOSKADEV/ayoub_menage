<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseItem extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'purchase_id',
    'product_id',
    'name',
    'price',
    'quantity',
    'amount'
  ];

  public function purchase(){
    return $this->belongsTo(Purchase::class);
  }

  public function product(){
    return $this->belongsTo(Product::class);
  }
}
