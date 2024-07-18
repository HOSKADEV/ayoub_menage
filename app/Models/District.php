<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
    [
      'id',
      'wilaya_id',
      'name',
      'display_name',
      'longitude',
      'latitude',
    ];

    protected $casts = [
      'wilaya_id' => 'integer',
      'longitude' => 'double',
      'latitude' => 'double',
    ];

    public function wilayaDis()
    {
      return $this->belongsTo(Wilaya::class, 'wilaya_id');
    }

    public function order()
    {
      return $this->hasMany(Order::class);
    }
}
