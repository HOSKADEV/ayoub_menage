<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $fillable = [
      'payable_id',
      'payable_type',
      'amount',
      'paymnet_method',
      'is_paid',
      'paid_at',
      'receipt',
    ];


    public function payable(){
      return $this->morphTo();
    }
}
