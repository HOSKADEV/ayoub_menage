<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
  use HasFactory, SoftDeletes, SoftCascadeTrait;

  protected $fillable = [
    'district_id',
    'name',
    'phone',
    'image',
    'longitude',
    'latitude'
  ];

  public function district(){
    return $this->belongsTo(District::class);
  }

  public function orders(){
    return $this->hasMany(Order::class);
  }

  public function payments(){
    return $this->morphMany(Payment::class, 'payable');
  }
}
