<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $fillable = [
      'supplier_id',
      'receipt',
      'total_amount',
      'paid_amount'
    ];

    protected $softCascade = ['items'];

    public function supplier(){
      return $this->belongsTo(Suppliers::class);
    }

    public function items(){
      return $this->hasMany(PurchaseItem::class);
    }

    public function total(){
      $total_amount = $this->items()->sum('amount');
      if($this->total_amount != $total_amount ){
        $this->total_amount = $total_amount;
        $this->save();
      }
      return $total_amount;
    }
}
