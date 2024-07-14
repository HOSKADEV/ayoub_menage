<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilaya extends Model
{
    use HasFactory,SoftDeletes,SoftCascadeTrait;

    protected $table = 'wilayas';

    protected $fillable = [
      'id',
      'name',
      'display_name',
      'delivery_price',
      'longitude',
      'latitude',
      'created_at',
      'updated_at'
    ];

    protected $casts = [
      'delivery_price' => 'double',
      'longitude' => 'double',
      'latitude' => 'double',
    ];

    public function orders()
    {
      return $this->hasMany(Order::class);
    }

    public function wilayasOrder()
    {
      return Order::where('wilayas_id', $this->id)->first();
    }

    public function district()
    {
      return $this->hasMany(District::class, 'wilaya_id');
    }
}
